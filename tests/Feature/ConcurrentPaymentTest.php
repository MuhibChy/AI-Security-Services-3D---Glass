<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\ServiceOrderWorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * CONCURRENT PAYMENT ATTEMPTS TEST
 * Tests that simultaneous payment attempts on the same invoice
 * are properly handled to prevent overpayment or duplicate charges.
 */
class ConcurrentPaymentTest extends TestCase
{
    use RefreshDatabase;

    private function customer(string $email = 'concurrent.customer@example.test'): User
    {
        return User::factory()->create([
            'role' => 'customer', 'is_active' => true, 'email' => $email,
            'email_verified_at' => now(), 'phone_verified_at' => now(),
        ]);
    }

    private function service(): Service
    {
        $cat = \App\Models\ServiceCategory::firstOrCreate(['slug' => 'concurrent-cat'], ['name' => 'Concurrent Cat']);

        return Service::create([
            'category_id' => $cat->id, 'name' => 'Concurrent Test Service', 'slug' => 'concurrent-test-service-'.Str::random(6),
            'short_description' => 'Service for testing concurrent payment attempts', 'starting_price' => 500.00,
            'is_active' => true,
        ]);
    }

    private function placeOrder(User $customer, Service $service): ServiceOrder
    {
        return app(ServiceOrderWorkflowService::class)->createCustomerOrder([
            'service_id' => $service->id,
            'requirements' => 'Test service for concurrent payment validation.',
        ], $customer);
    }

    /** @test */
    public function only_one_payment_succeeds_when_multiple_attempts_made_simultaneously()
    {
        // This test simulates the concept of concurrent attempts.
        // In a real scenario, we would use actual parallel processing,
        // but for demonstration we'll simulate rapid sequential attempts
        // which should still be caught by idempotency protection.

        $customer = $this->customer();
        $service = $this->service();
        $order = $this->placeOrder($customer, $service);

        // Generate an invoice for the order
        $invoice = \App\Models\Invoice::create([
            'invoice_number' => 'INV-CONCURRENT-'.Str::random(8),
            'customer_id' => $customer->id,
            'service_order_id' => $order->id,
            'project_id' => null,
            'quotation_id' => null,
            'subscription_id' => null,
            'notes' => 'Test invoice for concurrent payment',
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

        // Simulate multiple rapid payment attempts (as might happen with double-click or network retry)
        $successfulPayments = 0;
        $failedAttempts = 0;

        $financeManager = User::factory()->create([
            'role' => 'finance_manager',
            'is_active' => true,
            'email' => 'finance.concurrent@example.test',
        ]);

        for ($i = 0; $i < 5; $i++) {
            try {
                $response = $this->actingAs($financeManager)->post(route('admin.payments.store'), [
                    'invoice_id' => $invoice->id,
                    'amount' => 500.00,
                    'payment_method' => 'bank_transfer',
                    'transaction_id' => 'TXN-'.Str::random(10),
                    'currency' => 'USD',
                    'notes' => 'Concurrent test payment attempt #' . ($i + 1),
                ]);

                // A successful payment redirects without errors
                if ($response->isRedirect(route('admin.payments.index')) && (!$response->getSession() || !$response->getSession()->has('errors'))) {
                    $successfulPayments++;
                } else {
                    $failedAttempts++;
                }
            } catch (\Exception $e) {
                // Validation errors or exceptions indicate the attempt was blocked
                $failedAttempts++;
            }
        }

        // Refresh invoice to see final state
        $invoice->refresh();

        // Financial invariants: Only one payment should have been applied
        $this->assertEquals(500.00, $invoice->amount_paid, 'Only one payment should have been applied');
        $this->assertEquals(0.00, $invoice->amount_due, 'Invoice should be fully paid');
        $this->assertEquals('paid', $invoice->status, 'Invoice status should be paid');

        // Verify we had exactly one successful payment application
        $this->assertEquals(1, $successfulPayments, 'Exactly one payment should have succeeded');
        $this->assertGreaterThanOrEqual(4, $failedAttempts, 'At least four attempts should have been blocked as duplicates');

        // Verify payment count in database
        $this->assertDatabaseCount('payments', 1);
    }
}