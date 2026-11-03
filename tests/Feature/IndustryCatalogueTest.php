<?php

namespace Tests\Feature;

use App\Models\Industry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndustryCatalogueTest extends TestCase
{
    use RefreshDatabase;

    protected function newIndustryNames(): array
    {
        return array_column(config('industries.new'), 'name');
    }

    public function test_existing_sectors_are_preserved_on_the_industries_page()
    {
        $res = $this->get('/industries');

        $res->assertOk();
        foreach ([
            'Healthcare & Life Sciences',
            'Financial Services & FinTech',
            'E-Commerce & Retail',
            'Education & EdTech',
            'Manufacturing & Industrial',
            'Government & Public Sector',
            'Legal & Professional Services',
            'Energy & Utilities',
            'Media & Entertainment',
            'Logistics, Transport & Distribution',
            'Hospitality & Accommodation',
            'Construction & Property Management',
            'Nonprofits & Membership Organisations',
            'Automotive, Fleet & Vehicle Services',
            'Agriculture & Food Processing',
            'Telecommunications & Internet Providers',
            'Recruitment & Staffing Agencies',
            'Sports, Leisure & Fitness Clubs',
            'Home Services & Field Trades',
        ] as $name) {
            $res->assertSee($name);
        }
    }

    public function test_all_twenty_new_industries_render_on_the_industries_page()
    {
        $res = $this->get('/industries');

        $res->assertOk();
        foreach ($this->newIndustryNames() as $name) {
            $res->assertSee($name);
        }
        $this->assertCount(20, $this->newIndustryNames());
    }

    public function test_all_twenty_new_industries_render_on_the_homepage()
    {
        $res = $this->get('/');

        $res->assertOk();
        $res->assertSee('Industries We Serve');
        foreach ($this->newIndustryNames() as $name) {
            $res->assertSee($name);
        }
    }

    public function test_each_new_industry_has_a_detail_page_linked_from_homepage()
    {
        $home = $this->get('/');
        $home->assertOk();

        foreach (config('industries.new') as $industry) {
            $url = route('industries.show', $industry['slug']);
            $home->assertSee($url, false);

            $res = $this->get($url);
            $res->assertOk();
            $res->assertSee($industry['name']);
            $res->assertSee('Industry overview');
            $res->assertSee('Common IT challenges');
            $res->assertSee('Recommended IT support services');
            $res->assertSee('Cybersecurity requirements');
            $res->assertSee('Technology requirements');
            $res->assertSee('Benefits of outsourced IT support');
            $res->assertSee($industry['seo_title']);
        }
    }

    public function test_each_new_industry_has_a_relevant_image_asset()
    {
        foreach (config('industries.new') as $industry) {
            $path = public_path('images/industries/'.$industry['slug'].'.svg');
            $this->assertFileExists($path);
            $this->assertStringContainsString('<svg', file_get_contents($path));
            $this->assertNotEmpty($industry['image_alt']);
        }

        $res = $this->get('/');
        $res->assertSee('/images/industries/real-estate-agencies-brokers.svg', false);
        $res->assertSee('loading="lazy"', false);
    }

    public function test_unknown_industry_returns_404()
    {
        $this->get('/industries/not-a-real-industry')->assertNotFound();
    }

    public function test_admin_can_manage_industries_without_touching_existing_rows()
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $originalIds = Industry::orderBy('id')->pluck('id')->all();

        $this->actingAs($admin)->get(route('admin.industries.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.industries.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.industries.edit', Industry::firstOrFail()))->assertOk();

        $this->actingAs($admin)->post(route('admin.industries.store'), [
            'name' => 'Test Industry Sector',
            'slug' => 'test-industry-sector',
            'category' => 'Testing',
            'description' => 'A temporary industry record created by the test suite.',
            'challenges' => "Challenge one\nChallenge two",
            'services' => "Service one\nService two",
            'cybersecurity' => 'Security one',
            'technology' => 'Technology one',
            'benefits' => 'Benefit one',
            'is_active' => '1',
            'show_on_homepage' => '1',
            'sort_order' => '99',
        ])->assertRedirect(route('admin.industries.index'));

        $created = Industry::where('slug', 'test-industry-sector')->firstOrFail();
        $this->assertSame(['Challenge one', 'Challenge two'], $created->challenges);

        $this->actingAs($admin)->put(route('admin.industries.update', $created), [
            'name' => 'Updated Test Industry',
            'slug' => 'test-industry-sector',
            'description' => 'Updated description for the test industry.',
            'is_active' => '1',
            'show_on_homepage' => '0',
            'sort_order' => '98',
        ])->assertRedirect(route('admin.industries.index'));

        $this->assertSame('Updated Test Industry', $created->fresh()->name);
        $this->assertFalse($created->fresh()->show_on_homepage);

        $this->actingAs($admin)->delete(route('admin.industries.destroy', $created))
            ->assertRedirect(route('admin.industries.index'));

        $this->assertNull(Industry::find($created->id));
        $this->assertSame($originalIds, Industry::orderBy('id')->pluck('id')->all());
    }

    public function test_database_seed_has_no_duplicate_slugs_and_preserves_all_twenty()
    {
        $this->assertSame(20, Industry::count());
        $this->assertSame(20, Industry::distinct()->count('slug'));
        $this->assertSame(0, Industry::where('is_active', false)->count());
        $this->assertSame(20, Industry::where('show_on_homepage', true)->count());
    }
}
