<?php

namespace Tests\Feature;

use App\Models\Service;
use Database\Seeders\AssetLifecycleCategorySeeder;
use Database\Seeders\ExpandedServiceCatalogueSeeder;
use Database\Seeders\ExtendedItServicesSeeder;
use Database\Seeders\ExtraServicesSeeder;
use Database\Seeders\ServiceCatalogueSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * Public "Skills & Capabilities" matrix: taxonomy integrity, stable-slug
 * matching to the sales catalogue, and the unmatched fallback.
 */
class CapabilitySkillsTest extends TestCase
{
    use RefreshDatabase;

    private function groups(): array
    {
        return config('capabilities.groups', []);
    }

    private function serviceMap(): array
    {
        return config('capabilities.service_map', []);
    }

    private function items(): Collection
    {
        return collect($this->groups())->flatMap(fn ($g) => $g['items'] ?? []);
    }

    private function seedCatalogue(): void
    {
        $this->seed([
            ServiceCatalogueSeeder::class,
            ExpandedServiceCatalogueSeeder::class,
            ExtraServicesSeeder::class,
            AssetLifecycleCategorySeeder::class,
            ExtendedItServicesSeeder::class,
        ]);
    }

    /** @test */
    public function taxonomy_has_161_unique_capabilities_in_11_groups()
    {
        $this->assertCount(11, $this->groups());

        $items = $this->items();
        $this->assertCount(161, $items);
        $this->assertCount(161, $items->pluck('slug')->unique(), 'capability slugs must be unique');
        $this->assertCount(161, $items->pluck('name')->unique(), 'capability names must be unique');

        foreach ($items as $item) {
            $this->assertArrayHasKey('slug', $item);
            $this->assertArrayHasKey('name', $item);
            $this->assertMatchesRegularExpression('/^[a-z0-9]+(-[a-z0-9]+)*$/', $item['slug']);
        }
    }

    /** @test */
    public function service_map_keys_are_real_capabilities_and_values_are_slugs()
    {
        $capabilitySlugs = $this->items()->pluck('slug')->all();

        foreach ($this->serviceMap() as $capability => $serviceSlug) {
            $this->assertContains($capability, $capabilitySlugs, "service_map key '{$capability}' is not a capability slug");
            $this->assertMatchesRegularExpression('/^[a-z0-9]+(-[a-z0-9]+)*$/', $serviceSlug);
        }
    }

    /** @test */
    public function every_capability_resolves_to_an_active_catalogue_service()
    {
        $this->seedCatalogue();

        $map = $this->serviceMap();
        $activeSlugs = Service::where('is_active', true)->pluck('slug')->all();

        $unmatched = [];
        foreach ($this->items()->pluck('slug') as $capability) {
            $target = $map[$capability] ?? $capability;
            if (! in_array($target, $activeSlugs, true)) {
                $unmatched[] = "{$capability} => {$target}";
            }
        }

        $this->assertSame([], $unmatched, 'Unresolved capabilities: '.implode(', ', $unmatched));
    }

    /** @test */
    public function skills_page_lists_all_capabilities_and_groups()
    {
        $this->seedCatalogue();

        $res = $this->get(route('skills'));
        $res->assertOk();

        foreach ($this->groups() as $group) {
            $res->assertSee($group['name']);
        }

        foreach ($this->items() as $item) {
            $res->assertSee($item['name']);
        }

        $this->assertSame(161, substr_count($res->getContent(), 'Service available'));
        $this->assertSame(0, substr_count($res->getContent(), 'Available on request'));
    }

    /** @test */
    public function capabilities_link_to_the_correct_service_slugs()
    {
        $this->seedCatalogue();

        $res = $this->get(route('skills'));
        $res->assertOk();

        // Explicit service_map entries.
        $res->assertSee(route('services.show', 'desktop-support'), false);
        $res->assertSee(route('services.show', 'windows-troubleshooting-support'), false);
        // Self-slug match (capability slug equals a service slug).
        $res->assertSee(route('services.show', 'it-helpdesk-troubleshooting'), false);
        // The capability slug itself is never linked when it maps elsewhere.
        $res->assertDontSee(route('services.show', 'desktop-and-laptop-support'), false);
    }

    /** @test */
    public function unmatched_capabilities_fall_back_to_available_on_request()
    {
        $this->seedCatalogue();

        Service::where('slug', 'desktop-support')->update(['is_active' => false]);

        $res = $this->get(route('skills'));
        $res->assertOk();

        $this->assertSame(160, substr_count($res->getContent(), 'Service available'));
        $this->assertSame(1, substr_count($res->getContent(), 'Available on request'));
        $res->assertDontSee(route('services.show', 'desktop-support'), false);
    }

    /** @test */
    public function public_skills_route_is_public_while_admin_skills_requires_auth()
    {
        $this->get(route('skills'))->assertOk();
        $this->get(route('admin.ai.skills.index'))->assertRedirect();
    }
}
