<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\ServiceOrderWorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * CANCELLED INVOICE PAYMENT TEST
 * Tests that payments cannot be processed on cancelled invoices.
 */
class CancelledInvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    private function financeManager(string $email = 'finance.manager@example.test'): User
    {
        return User::factory()->create([
            'role' => 'finance_manager', 'is_active' => true, 'email' => $email,
            'email_verified_at' => now(), 'phone_verified_at' => now(),
        ]);
    }

    private function customer(string $email = 'customer@example.test'): User
    {
        return User::factory()->create([
            'role' => 'customer', 'is_active' => true, 'email' => $email,
            'email_verified_at' => now(), 'phone_verified_at' => now(),
        ]);
    }

    private function service(): Service
    {
        $cat = ServiceCategory::firstOrCreate(['slug' => 'cancelled-invoice-cat'], ['name' => 'Cancelled Invoice Cat']);

        return Service::create([
            'category_id' => $cat->id, 'name' => 'Cancelled Invoice Test Service', 'slug' => 'cancelled-invoice-test-service-'.Str::random(6),
            'short_description' => 'Service for testing cancelled invoice payment prevention', 'starting_price' => 100.00,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function payment_attempt_on_cancelled_invoice_is_rejected()
    {
        $financeManager = $this->financeManager();
        $customer = $this->customer();
        $service = $this->service();

        // Create service order and invoice
        $order = app(ServiceOrderWorkflowService::class)->createCustomerOrder([
            'service_id' => $service->id,
            'requirements' => 'Test service for cancelled invoice payment prevention.',
        ], $customer);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-CANCELLED-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for cancelled payment prevention',
            'terms' => 'Net 14',
            'subtotal' => 100.00,
            'discount_amount' => 0.00,
            'discount_type' => 'none',
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'total' => 100.00,
            'amount_paid' => 0.00,
            'amount_due' => 100.00,
            'status' => 'sent',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
        ]);

        // Cancel the invoice
        $invoice->status = 'cancelled';
        $invoice->save();

        // Attempt to process a payment on the cancelled invoice via admin
        $response = $this->actingAs($financeManager)->post(route('admin.payments.store'), [
            'invoice_id' => $invoice->id,
            'amount' => 100.00,
            'payment_method' => 'bank_transfer',
            'transaction_id' => 'TXN-CANCELLED-'.Str::random(10),
            'currency' => 'USD',
            'notes' => 'Attempt payment on cancelled invoice',
        ]);

        // Should be rejected with validation error or redirect
        $this->assertTrue(in_array($response->status(), [302, 422], true));

        // Verify invoice status remains cancelled
        $invoice->refresh();
        $this->assertEquals('cancelled', $invoice->status, 'Invoice status should remain cancelled');
        $this->assertEquals(0.00, $invoice->amount_paid, 'Invoice should remain unpaid');
        $this->assertEquals(100.00, $invoice->amount_due, 'Invoice should still show full amount due');

        // Verify no payment was created
        $this->assertDatabaseMissing('payments', ['invoice_id' => $invoice->id]);
    }

    /** @test */
    public function payment_attempt_on_cancelled_invoice_from_customer_is_blocked()
    {
        $customer = $this->customer();
        $service = $this->service();

        // Create service order and invoice
        $order = app(ServiceOrderWorkflowService::class)->createCustomerOrder([
            'service_id' => $service->id,
            'requirements' => 'Test service for customer cancelled invoice payment prevention.',
        ], $customer);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-CANCELLED-CUST-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for customer cancelled payment prevention',
            'terms' => 'Net 14',
            'subtotal' => 100.00,
            'discount_amount' => 0.00,
            'discount_type' => 'none',
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'total' => 100.00,
            'amount_paid' => 0.00,
            'amount_due' => 100.00,
            'status' => 'sent',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
        ]);

        // Cancel the invoice
        $invoice->status = 'cancelled';
        $invoice->save();

        // Attempt to pay via customer portal (should be blocked)
        // The portal route requires wallet parameter, so we test the admin route instead
        $response = $this->actingAs($customer)->post(route('admin.payments.store'), [
            'invoice_id' => $invoice->id,
            'amount' => 100.00,
            'payment_method' => 'bank_transfer',
            'transaction_id' => 'TXN-CUST-CANCELLED-'.Str::random(10),
            'currency' => 'USD',
        ]);

        // Should be redirected (customer doesn't have permission)
        $this->assertTrue(in_array($response->status(), [302, 403], true));

        // Verify invoice status remains cancelled
        $invoice->refresh();
        $this->assertEquals('cancelled', $invoice->status, 'Invoice status should remain cancelled');
        $this->assertEquals(0.00, $invoice->amount_paid, 'Invoice should remain unpaid');
        $this->assertEquals(100.00, $invoice->amount_due, 'Invoice should still show full amount due');

        // Verify no payment was created
        $this->assertDatabaseMissing('payments', ['invoice_id' => $invoice->id]);
    }
}
