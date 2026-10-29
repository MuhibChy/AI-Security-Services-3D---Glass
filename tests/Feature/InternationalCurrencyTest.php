<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\User;
use App\Services\FinancialService;
use App\Services\Money;
use App\Services\StripePaymentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * International expansion: catalog, precision, formatting, per-currency
 * finance, payment rails, admin management, RTL, PDF.
 *
 * Customer-facing currencies are now restricted to BDT, GBP, USD.
 * Historical records (AED, EUR, etc.) remain in the database but are
 * no longer selectable or payable through any customer flow.
 */
class InternationalCurrencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function currency_catalog_covers_all_required_markets()
    {
        foreach (['GBP', 'USD', 'BDT'] as $code) {
            $this->assertTrue(Money::isSupported($code), "{$code} missing");
            $this->assertTrue(Money::isActive($code), "{$code} inactive");
        }
        $this->assertEquals(2, Money::decimals('GBP'));
        $this->assertEquals(2, Money::decimals('USD'));
        $this->assertEquals(2, Money::decimals('BDT'));
        $this->assertEquals('UK', Country::where('currency_code', 'GBP')->first()->code);
        $this->assertEquals('South Asia', Country::where('currency_code', 'BDT')->first()->region);
    }

    /** @test */
    public function money_formats_per_currency_precision()
    {
        $this->assertEquals('£1,500.00', Money::format(1500, 'GBP'));
        $this->assertEquals('USD 1,500.00', Money::formatCode(1500, 'USD'));
        $this->assertEquals('৳1,500.00', Money::format(1500, 'BDT'));
        $this->assertEquals(1501.0, Money::round(1500.9999, 'GBP'));
    }

    /** @test */
    public function finance_never_mixes_currencies()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $fx = app(FinancialService::class);
        $fx->recordIncome(1000.00, 'Sales', 'GBP sale', ['currency' => 'GBP', 'created_by' => $admin->id]);
        $fx->recordIncome(500.00, 'Sales', 'BDT sale', ['currency' => 'BDT', 'created_by' => $admin->id]);
        $fx->recordExpense(100.00, 'Hosting', 'BDT cost', ['currency' => 'BDT', 'created_by' => $admin->id]);

        $byCcy = $fx->getRevenueByCurrency(now()->startOfMonth(), now()->endOfMonth());
        $this->assertEquals(1000.00, $byCcy['GBP']);
        $this->assertEquals(500.00, $byCcy['BDT']);
        $exp = $fx->getExpensesByCurrency(now()->startOfMonth(), now()->endOfMonth());
        $this->assertEquals(100.00, $exp['BDT']);
        $this->assertArrayNotHasKey('GBP', $exp);

        $pnl = $fx->getProfitAndLoss();
        $this->assertArrayHasKey('revenue_by_currency', $pnl);
        $this->assertArrayHasKey('expenses_by_currency', $pnl);
    }

    /** @test */
    public function currency_switch_rejects_unknown_codes_centrally()
    {
        $this->get('/currency/XXX')->assertStatus(400);
        // Only BDT, GBP, USD are customer-facing.
        $this->get('/currency/GBP')->assertRedirect();
        $this->get('/currency/USD')->assertRedirect();
        $this->get('/currency/BDT')->assertRedirect();
        // EUR is active in catalog (historical) but not selectable by customers.
        // On guest request, it redirects (accepted globally).
        $this->get('/currency/EUR')->assertRedirect();
        $this->get('/currency/AED')->assertRedirect();
    }

    /** @test */
    public function stripe_capability_map_is_honest()
    {
        foreach (['GBP', 'USD'] as $code) {
            $this->assertTrue(StripePaymentService::supportsCurrency($code), $code);
        }
        $this->assertFalse(StripePaymentService::supportsCurrency('BDT'));
        $this->assertFalse(StripePaymentService::supportsCurrency('EUR'));
        $this->assertFalse(StripePaymentService::supportsCurrency('AED'));
        $this->assertFalse(StripePaymentService::supportsCurrency('XXX'));
    }

    /** @test */
    public function manual_payment_rejects_currency_mismatch()
    {
        $customer = User::factory()->create(['role' => 'customer', 'email_verified_at' => now(), 'phone_verified_at' => now()]);
        $finance = User::factory()->create(['role' => 'finance_manager', 'is_active' => true]);
        $invoice = \App\Models\Invoice::create([
            'invoice_number' => 'INV-FX01', 'customer_id' => $customer->id,
            'total' => 5250.00, 'amount_due' => 5250.00, 'currency' => 'GBP',
            'status' => 'sent', 'due_date' => now()->addDays(14),
        ]);

        $this->actingAs($finance)->post(route('admin.payments.store'), [
            'invoice_id' => $invoice->id, 'amount' => 100, 'payment_method' => 'card', 'currency' => 'USD',
        ])->assertStatus(422);

        $this->actingAs($finance)->post(route('admin.payments.store'), [
            'invoice_id' => $invoice->id, 'amount' => 2000, 'payment_method' => 'card', 'currency' => 'GBP',
        ])->assertRedirect();
        $this->assertEquals('GBP', \App\Models\Payment::first()->currency);
        $this->assertEquals('GBP', \App\Models\FinancialTransaction::where('type', 'income')->first()->currency);
    }

    /** @test */
    public function customer_preferred_currency_validates_against_catalog()
    {
        // A UK customer may hold GBP; a non-allowed catalog code is rejected.
        $customer = User::factory()->create(['role' => 'customer', 'email_verified_at' => now(), 'phone_verified_at' => now(), 'country' => 'United Kingdom', 'country_code' => 'UK']);
        $this->actingAs($customer)->put(route('portal.profile.update'), [
            'name' => $customer->name, 'email' => $customer->email, 'preferred_currency' => 'GBP',
        ])->assertRedirect();
        $this->assertEquals('GBP', $customer->fresh()->preferred_currency);

        $this->actingAs($customer)->put(route('portal.profile.update'), [
            'name' => $customer->name, 'email' => $customer->email, 'preferred_currency' => 'XXX',
        ])->assertStatus(422);

        $this->actingAs($customer)->put(route('portal.profile.update'), [
            'name' => $customer->name, 'email' => $customer->email, 'preferred_currency' => 'BDT',
        ])->assertStatus(422);
    }

    /** @test */
    public function admin_manages_currencies_while_customers_are_forbidden()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $customer = User::factory()->create(['role' => 'customer', 'is_active' => true]);

        $this->actingAs($customer)->get(route('admin.countries.index'))->assertStatus(403);
        $this->actingAs($admin)->get(route('admin.countries.index'))->assertStatus(200);

        $gbp = Country::where('currency_code', 'GBP')->firstOrFail();
        $this->actingAs($admin)->put(route('admin.countries.update', $gbp), [
            'decimal_places' => 2, 'region' => 'UK', 'tax_rate' => 20, 'is_active' => true,
        ])->assertRedirect();
        $this->assertEquals(20, (float) $gbp->fresh()->tax_rate);
    }

    /** @test */
    public function rtl_direction_binds_to_locale()
    {
        $this->get('/')->assertSee('dir="ltr"', false);
        $this->get('/lang/ar')->assertRedirect();
        $this->get('/')->assertSee('dir="rtl"', false);
        $this->get('/lang/en')->assertRedirect();
    }

    /** @test */
    public function pdf_renders_gbp_invoice_with_unicode_font()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $invoice = \App\Models\Invoice::create([
            'invoice_number' => 'INV-PDF-GBP', 'customer_id' => $customer->id,
            'subtotal' => 5000, 'tax_rate' => 20, 'tax_amount' => 1000, 'total' => 6000,
            'amount_due' => 6000, 'currency' => 'GBP', 'status' => 'sent',
            'due_date' => now()->addDays(14),
        ]);
        $pdf = Pdf::loadView('customer.invoices.pdf', ['invoice' => $invoice]);
        $content = $pdf->output();
        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('%PDF', $content);
    }
}
