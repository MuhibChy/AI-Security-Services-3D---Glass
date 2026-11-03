<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\ServiceOrderWorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * REFUND VALIDATION TEST
 * Tests that refunds cannot exceed the refundable amount of a payment.
 */
class RefundValidationTest extends TestCase
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
        $cat = ServiceCategory::firstOrCreate(['slug' => 'refund-test-cat'], ['name' => 'Refund Test Cat']);

        return Service::create([
            'category_id' => $cat->id, 'name' => 'Refund Test Service', 'slug' => 'refund-test-service-'.Str::random(6),
            'short_description' => 'Service for testing refund validation', 'starting_price' => 100.00,
            'is_active' => true,
        ]);
    }

    private function placeOrder(User $customer, Service $service): ServiceOrder
    {
        return app(ServiceOrderWorkflowService::class)->createCustomerOrder([
            'service_id' => $service->id,
            'requirements' => 'Test service for refund validation.',
        ], $customer);
    }

    /** @test */
    public function refund_exceeding_refundable_amount_is_rejected()
    {
        $financeManager = $this->financeManager();
        $customer = $this->customer();
        $service = $this->service();
        $order = $this->placeOrder($customer, $service);

        // Create invoice for the order
        $invoice = Invoice::create([
            'invoice_number' => 'INV-REFUND-TEST-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for refund validation',
            'terms' => 'Net 14',
            'subtotal' => 100.00,
            'discount_amount' => 0.00,
            'discount_type' => 'none',
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'total' => 100.00,
            'amount_paid' => 100.00,
            'amount_due' => 0.00,
            'status' => 'paid',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
        ]);

        // Create completed payment for the invoice
        $payment = Payment::create([
            'payment_number' => 'PAY-REFUND-TEST-'.rand(10000,99999),
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => 100.00,
            'currency' => 'USD',
            'status' => 'completed',
            'payment_method' => 'bank_transfer',
            'refunded_amount' => 0.00,
        ]);

        // Attempt to refund more than the payment amount (150%)
        $response = $this->actingAs($financeManager)->post(route('admin.payments.refund', $payment->id), [
            'amount' => 150.00, // Exceeds refundable amount of 100.00
            'reason' => 'Testing refund validation - attempting over-refund',
        ]);

        // Should be rejected with validation error
        $response->assertStatus(422);

        // Verify payment unchanged
        $payment->refresh();
        $this->assertEquals(0.00, $payment->refunded_amount, 'Payment refunded amount should remain zero');
        $this->assertEquals('completed', $payment->status, 'Payment status should remain completed');
    }

    /** @test */
    public function partial_refund_is_allowed_up_to_refundable_amount()
    {
        $financeManager = $this->financeManager();
        $customer = $this->customer();
        $service = $this->service();
        $order = $this->placeOrder($customer, $service);

        // Create invoice for the order
        $invoice = Invoice::create([
            'invoice_number' => 'INV-PARTIAL-REFUND-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for partial refund',
            'terms' => 'Net 14',
            'subtotal' => 100.00,
            'discount_amount' => 0.00,
            'discount_type' => 'none',
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'total' => 100.00,
            'amount_paid' => 100.00,
            'amount_due' => 0.00,
            'status' => 'paid',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
        ]);

        // Create completed payment for the invoice
        $payment = Payment::create([
            'payment_number' => 'PAY-PARTIAL-REFUND-'.rand(10000,99999),
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => 100.00,
            'currency' => 'USD',
            'status' => 'completed',
            'payment_method' => 'bank_transfer',
            'refunded_amount' => 0.00,
        ]);

        // Refund exactly half - should succeed
        $response = $this->actingAs($financeManager)->post(route('admin.payments.refund', $payment->id), [
            'amount' => 50.00,
            'reason' => 'Testing partial refund',
        ]);

        $response->assertRedirect(); // Should succeed

        // Verify payment updated correctly
        $payment->refresh();
        $this->assertEquals(50.00, $payment->refunded_amount, 'Payment should have 50 refunded');
        $this->assertEquals('completed', $payment->status, 'Payment status should remain completed');

        // Verify invoice status should change to partially_paid (since partially refunded)
        $invoice->refresh();
        $this->assertEquals('partially_paid', $invoice->status, 'Invoice status should be partially_paid after partial refund');
    }

    /** @test */
    public function refund_exactly_equal_to_payment_amount_is_allowed()
    {
        $financeManager = $this->financeManager();
        $customer = $this->customer();
        $service = $this->service();
        $order = $this->placeOrder($customer, $service);

        // Create invoice for the order
        $invoice = Invoice::create([
            'invoice_number' => 'INV-FULL-REFUND-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for full refund',
            'terms' => 'Net 14',
            'subtotal' => 75.50,
            'discount_amount' => 0.00,
            'discount_type' => 'none',
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'total' => 75.50,
            'amount_paid' => 75.50,
            'amount_due' => 0.00,
            'status' => 'paid',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
        ]);

        // Create completed payment for the invoice
        $payment = Payment::create([
            'payment_number' => 'PAY-FULL-REFUND-'.rand(10000,99999),
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => 75.50,
            'currency' => 'USD',
            'status' => 'completed',
            'payment_method' => 'bank_transfer',
            'refunded_amount' => 0.00,
        ]);

        // Refund the full amount - should succeed
        $response = $this->actingAs($financeManager)->post(route('admin.payments.refund', $payment->id), [
            'amount' => 75.50,
            'reason' => 'Testing full refund',
        ]);

        $response->assertRedirect(); // Should succeed

        // Verify payment fully refunded
        $payment->refresh();
        $this->assertEquals(75.50, $payment->refunded_amount, 'Payment should be fully refunded');
    }
}