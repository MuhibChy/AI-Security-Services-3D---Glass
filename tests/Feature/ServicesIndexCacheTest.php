<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use Database\Seeders\ServiceCatalogueSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Public services index render cache: guests get a cached render,
 * authenticated users get the live personalized render, and catalogue
 * mutations invalidate the guest cache immediately.
 */
class ServicesIndexCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'array']);
        cache()->clear();
        $this->seed(ServiceCatalogueSeeder::class);
        // Catalogue seeds may ship inactive services; the index only shows active.
        Service::query()->update(['is_active' => true]);
        ServiceCategory::query()->update(['is_active' => true]);
    }

    private function guestGet(): \Illuminate\Testing\TestResponse
    {
        return $this->get(route('services.index'));
    }

    /** @test */
    public function guests_receive_identical_payloads_from_the_cache()
    {
        $first = $this->guestGet()->assertOk();
        $second = $this->guestGet()->assertOk();

        $firstBody = $first->getContent();
        $this->assertGreaterThan(0, strlen($firstBody));
        $this->assertSame($firstBody, $second->getContent());
    }

    /** @test */
    public function catalogue_mutation_invalidates_the_guest_cache()
    {
        $original = $this->guestGet()->getContent();

        // Deactivate a service through an individual save (observer fires).
        $svc = Service::where('is_active', true)->firstOrFail();
        $svc->is_active = false;
        $svc->save();

        // Stale cached payload must be gone: guest sees updated catalogue.
        $after = $this->guestGet()->getContent();
        $this->assertNotSame($original, $after);
        $this->assertStringNotContainsString($svc->name, $after);
    }

    /** @test */
    public function category_mutation_invalidates_the_guest_cache()
    {
        $original = $this->guestGet()->getContent();

        $cat = ServiceCategory::where('is_active', true)->firstOrFail();
        $cat->name = 'QA Renamed Category '.uniqid();
        $cat->save();

        $after = $this->guestGet()->getContent();
        $this->assertStringContainsString($cat->name, $after);
        $this->assertNotSame($original, $after);
    }

    /** @test */
    public function authenticated_users_bypass_the_cache()
    {
        $user = $this->customerUser();

        $guest = $this->guestGet()->getContent();
        $authed = $this->actingAs($user)->get(route('services.index'))->assertOk()->getContent();

        // Authenticated layout differs (auth nav block), so it must not be
        // served from the guest cache.
        $this->assertNotSame($guest, $authed);
    }

    private function customerUser(): \App\Models\User
    {
        return \App\Models\User::factory()->create(['role' => 'customer', 'is_active' => true]);
    }
}
