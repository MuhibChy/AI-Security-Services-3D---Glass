<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\ServiceOrderWorkflowService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * WALLET PAYMENT FLOW TEST
 * Tests the end-to-end flow of funding a wallet and using it to pay an invoice.
 */
class WalletPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private function customer(string $email = 'wallet.customer@example.test'): User
    {
        return User::factory()->create([
            'role' => 'customer', 'is_active' => true, 'email' => $email,
            'email_verified_at' => now(), 'phone_verified_at' => now(),
        ]);
    }

    private function service(): Service
    {
        $cat = ServiceCategory::firstOrCreate(['slug' => 'wallet-cat'], ['name' => 'Wallet Cat']);

        return Service::create([
            'category_id' => $cat->id, 'name' => 'Wallet Test Service', 'slug' => 'wallet-test-service-'.Str::random(6),
            'short_description' => 'Service for testing wallet payment flow', 'starting_price' => 300.00,
            'is_active' => true,
        ]);
    }

    private function placeOrder(User $customer, Service $service): ServiceOrder
    {
        return app(ServiceOrderWorkflowService::class)->createCustomerOrder([
            'service_id' => $service->id,
            'requirements' => 'Test service for wallet payment validation.',
        ], $customer);
    }

    /** @test */
    public function customer_can_fund_wallet_and_use_it_to_pay_invoice()
    {
        $customer = $this->customer();
        $service = $this->service();
        $order = $this->placeOrder($customer, $service);

        // Generate an invoice for the order
        $invoice = Invoice::create([
            'invoice_number' => 'INV-WALLET-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for wallet payment',
            'terms' => 'Net 14',
            'subtotal' => 300.00,
            'discount_amount' => 0.00,
            'discount_type' => 'none',
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'total' => 300.00,
            'amount_paid' => 0.00,
            'amount_due' => 300.00,
            'status' => 'sent',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
        ]);

        // Step 1: Fund the wallet
        $wallet = app(WalletService::class)->for($customer);
        app(WalletService::class)->creditDeposit(
            $wallet,
            300.00,
            'bank_transfer',
            'WALLET-FUND-'.Str::random(10),
            'evt-'.Str::random(10),
            $customer->id
        );

        // Verify wallet balance increased
        $customer->refresh();
        $wallet = $customer->wallet;
        $this->assertNotNull($wallet, 'Customer should have a wallet');
        $this->assertEquals(300.00, (float) $wallet->balance, 'Wallet balance should be 300 after funding');

        // Step 2: Use wallet to pay invoice
        $walletPaymentResponse = $this->actingAs($customer)->post(route('portal.wallet.pay-invoice', $wallet->id), [
            'invoice_id' => $invoice->id,
        ]);

        $walletPaymentResponse->assertRedirect(); // Should succeed

        // Verify invoice is now paid
        $invoice->refresh();
        $this->assertEquals(300.00, (float) $invoice->amount_paid, 'Invoice should be fully paid');
        $this->assertEquals(0.00, (float) $invoice->amount_due, 'Invoice should have zero balance due');
        $this->assertEquals('paid', $invoice->status, 'Invoice status should be paid');

        // Verify wallet balance decreased
        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->balance, 'Wallet balance should be zero after payment');

        // Verify payment transaction created (via admin or portal)
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 300.00,
            'status' => 'completed',
        ]);

        // Verify wallet transaction created for the payment
        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'type' => 'invoice_payment',
            'amount' => 300.00,
        ]);
    }

    /** @test */
    public function wallet_insufficient_funds_prevents_invoice_payment()
    {
        $customer = $this->customer();
        $service = $this->service();
        $order = $this->placeOrder($customer, $service);

        // Generate an invoice for the order
        $invoice = Invoice::create([
            'invoice_number' => 'INV-WALLET-INSUFFICIENT-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for insufficient wallet funds',
            'terms' => 'Net 14',
            'subtotal' => 500.00,
            'discount_amount' => 0.00,
            'discount_type' => 'none',
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'total' => 500.00,
            'amount_paid' => 0.00,
            'amount_due' => 500.00,
            'status' => 'sent',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
        ]);

        // Fund wallet with less than invoice amount
        $wallet = app(WalletService::class)->for($customer);
        app(WalletService::class)->creditDeposit(
            $wallet,
            200.00,
            'bank_transfer',
            'WALLET-FUND-INSUFFICIENT-'.Str::random(10),
            'evt-'.Str::random(10),
            $customer->id
        );

        // Verify wallet balance
        $customer->refresh();
        $wallet = $customer->wallet;
        $this->assertEquals(200.00, (float) $wallet->balance, 'Wallet should have 200 after partial funding');

        // Attempt to pay invoice with insufficient funds
        $walletPaymentResponse = $this->actingAs($customer)->post(route('portal.wallet.pay-invoice', $wallet->id), [
            'invoice_id' => $invoice->id,
        ]);

        // Should be redirected back with error (validation/business failure)
        $walletPaymentResponse->assertRedirect();
        $walletPaymentResponse->assertSessionHas('error');

        // Verify invoice remains unpaid
        $invoice->refresh();
        $this->assertEquals(0.00, (float) $invoice->amount_paid, 'Invoice should remain unpaid');
        $this->assertEquals(500.00, (float) $invoice->amount_due, 'Invoice should still show full amount due');
        $this->assertEquals('sent', $invoice->status, 'Invoice status should remain sent');

        // Verify wallet balance unchanged
        $wallet->refresh();
        $this->assertEquals(200.00, (float) $wallet->balance, 'Wallet balance should remain 200 after failed payment attempt');
    }
}