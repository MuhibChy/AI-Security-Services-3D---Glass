<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\AuditService;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('invoice', 'customer');
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $payments = $query->latest()->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoices = Invoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->latest()->get();

        return view('admin.payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'currency' => 'nullable|string|max:3',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            $invoice = Invoice::lockForUpdate()->findOrFail($validated['invoice_id']);
            abort_if(in_array($invoice->status, ['cancelled'], true), 422, 'Cannot process payment on a cancelled invoice.');
            abort_if($invoice->status === 'paid', 422, 'Invoice is already fully paid.');

            if (! empty($validated['currency'])) {
                abort_if(strtoupper($validated['currency']) !== strtoupper($invoice->currency), 422, 'Currency mismatch between payment and invoice.');
            }

            $amount = round((float) $validated['amount'], 2);
            $due = round((float) $invoice->amount_due, 2);
            abort_if($amount > $due, 422, "Payment amount ({$amount}) exceeds the amount due ({$due}).");

            $currency = $invoice->currency ?: ($validated['currency'] ?? 'USD');
            $paymentMethod = $validated['payment_method'] ?? 'bank_transfer';
            $transactionId = ! empty($validated['transaction_id']) ? $validated['transaction_id'] : ('TXN-'.strtoupper(Str::random(10)));

            // Idempotency: skip if already recorded
            $existing = Payment::where('invoice_id', $invoice->id)->where('transaction_id', $transactionId)->lockForUpdate()->first();
            if ($existing) {
                return redirect()->route('admin.payments.index')->with('info', 'Payment already recorded.');
            }

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id,
                'service_order_id' => $invoice->service_order_id,
                'amount' => $amount,
                'currency' => $currency,
                'payment_method' => $paymentMethod,
                'transaction_id' => $transactionId,
                'status' => 'completed',
                'paid_at' => now(),
                'notes' => $validated['notes'] ?? ("Payment for invoice {$invoice->invoice_number}"),
            ]);

            // Update invoice balances
            $newPaid = round((float) $invoice->amount_paid + $amount, 2);
            $newDue = max(0, round((float) $invoice->total - $newPaid, 2));
            $invoice->amount_paid = $newPaid;
            $invoice->amount_due = $newDue;
            $invoice->status = $newDue == 0 ? 'paid' : 'partially_paid';
            if ($newDue == 0) {
                $invoice->paid_at = now();
            }
            $invoice->save();

            // Update linked ServiceOrder if present
            if ($invoice->service_order_id) {
                $order = \App\Models\ServiceOrder::lockForUpdate()->find($invoice->service_order_id);
                if ($order && ! in_array($order->status, ['closed', 'cancelled'], true)) {
                    $orderPaid = round((float) $order->amount_paid + $amount, 2);
                    $orderDue = max(0, round((float) $order->total - $orderPaid, 2));
                    $order->amount_paid = $orderPaid;
                    $order->amount_due = $orderDue;
                    if ($orderDue == 0) {
                        $order->payment_authorization = 'fully_paid';
                    } elseif ($orderPaid >= round((float) $order->total * 0.5, 2)) {
                        $order->payment_authorization = 'deposit_paid';
                    }
                    $order->save();

                    if (class_exists(\App\Models\Receipt::class)) {
                        \App\Models\Receipt::firstOrCreate(
                            ['payment_id' => $payment->id],
                            [
                                'receipt_number' => 'RCP-'.strtoupper(Str::random(8)),
                                'service_order_id' => $order->id,
                                'customer_id' => $order->customer_id,
                                'invoice_id' => $invoice->id,
                                'amount' => $amount,
                                'remaining_balance' => $orderDue,
                                'currency' => $currency,
                                'issued_at' => now(),
                            ]
                        );
                    }
                }
            }

            // Record Income in Financial Ledger
            app(FinancialService::class)->recordIncome(
                $amount,
                'Invoice Payment',
                "Payment {$payment->payment_number} for invoice {$invoice->invoice_number}",
                [
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id,
                    'service_order_id' => $invoice->service_order_id,
                    'currency' => $currency,
                    'payment_method' => $paymentMethod,
                ]
            );

            AuditService::log('payment.recorded', 'payments', $payment, "Payment of {$currency} {$amount} recorded for invoice {$invoice->invoice_number}.");

            return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
        });
    }

    public function refund(Request $request, $id)
    {
        // Authorization check: only finance managers and admins can refund payments
        if (!auth()->user()->isFinanceManager() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500',
        ]);

        return DB::transaction(function () use ($validated, $id) {
            $payment = Payment::lockForUpdate()->findOrFail($id);
            abort_unless($payment->status === 'completed', 422, 'Only completed payments can be refunded.');
            $invoice = Invoice::lockForUpdate()->findOrFail($payment->invoice_id);
            abort_if(in_array($invoice->status, ['cancelled'], true), 422, 'Refunds cannot be issued against a cancelled invoice.');

            $refundable = round((float) $payment->amount - (float) $payment->refunded_amount, 2);
            abort_if((float) $validated['amount'] > $refundable, 422, "Refund exceeds the refundable remainder ({$refundable}).");

            $amount = round((float) $validated['amount'], 2);

            // Online gateway reversal first (outside the ledger transaction):
            // if Stripe holds the funds, they must actually move. API failure
            // aborts before any local record is created.
            $gatewayRefundId = null;
            if ($payment->gateway === 'stripe' && $payment->stripe_payment_intent_id
                && ! empty(config('services.stripe.secret'))) {
                try {
                    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                    $reversal = \Stripe\Refund::create([
                        'payment_intent' => $payment->stripe_payment_intent_id,
                        'amount' => (int) round($amount * 100),
                        'reason' => 'requested_by_customer',
                        'metadata' => ['payment_id' => $payment->id, 'reason' => mb_substr($validated['reason'], 0, 200)],
                    ]);
                    $gatewayRefundId = $reversal->id ?? null;
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Stripe refund failed', ['payment' => $payment->id]);
                    abort(422, 'Stripe declined the reversal; no refund was recorded. Try again or refund from the Stripe Dashboard.');
                }
            }

            $refund = Payment::create([
                'payment_number' => 'RFD-'.strtoupper(Str::random(8)),
                'invoice_id' => $invoice->id,
                'customer_id' => $payment->customer_id,
                'service_order_id' => $payment->service_order_id,
                'amount' => $amount,
                'currency' => $payment->currency,
                'status' => 'refunded',
                'payment_method' => $payment->payment_method,
                'transaction_id' => $gatewayRefundId ?? ('REFUND-'.$payment->transaction_id),
                'notes' => "Refund of {$payment->payment_number}: {$validated['reason']}",
                'paid_at' => now(),
            ]);

            $payment->increment('refunded_amount', $amount);

            $invoice->amount_paid = round((float) $invoice->amount_paid - $amount, 2);
            $invoice->amount_due = round((float) $invoice->total - (float) $invoice->amount_paid, 2);
            if ($invoice->status === 'paid') {
                $invoice->status = 'partially_paid';
                $invoice->paid_at = null;
            }
            $invoice->save();

            if ($payment->service_order_id) {
                $order = \App\Models\ServiceOrder::lockForUpdate()->find($payment->service_order_id);
                if ($order) {
                    abort_if($order->status === 'closed', 422, 'Refunds cannot be issued against a closed order.');
                    $order->amount_paid = round((float) $order->amount_paid - $amount, 2);
                    $order->amount_due = round((float) $order->total - (float) $order->amount_paid, 2);
                    if ($order->payment_authorization === 'fully_paid') {
                        $order->payment_authorization = 'deposit_required';
                    }
                    $order->save();
                }
            }

            app(FinancialService::class)->recordRefund(
                $amount,
                "Refund {$refund->payment_number} ({$payment->payment_number})",
                ['payment_id' => $payment->id, 'refund_id' => $refund->id, 'invoice_id' => $invoice->id]
            );

            AuditService::log('refund', 'payments', $refund, "Refund of {$payment->currency} {$amount} against {$payment->payment_number}. Reason: {$validated['reason']}");

            if ($invoice->customer) {
                $invoice->customer->notify(new \App\Notifications\InvoiceCreatedNotification($invoice, 'refunded'));
            }

            return redirect()->back()->with('success', 'Refund recorded.');
        });
    }
}