<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Idempotent import of the 20 preferred industries. Existing rows are
     * never deleted or overwritten, so admin edits survive re-seeding.
     */
    public function run(): void
    {
        foreach (config('industries.new', []) as $industry) {
            Industry::firstOrCreate(
                ['slug' => $industry['slug']],
                [
                    'name' => $industry['name'],
                    'category' => $industry['category'] ?? null,
                    'description' => $industry['description'],
                    'icon' => $industry['icon'] ?? null,
                    'tags' => $industry['tags'] ?? [],
                    'overview' => $industry['overview'] ?? null,
                    'challenges' => $industry['challenges'] ?? [],
                    'services' => $industry['services'] ?? [],
                    'cybersecurity' => $industry['cybersecurity'] ?? [],
                    'technology' => $industry['technology'] ?? [],
                    'benefits' => $industry['benefits'] ?? [],
                    'image_path' => '/images/industries/'.$industry['slug'].'.svg',
                    'image_alt' => $industry['image_alt'] ?? null,
                    'seo_title' => $industry['seo_title'] ?? null,
                    'meta_description' => $industry['meta_description'] ?? null,
                    'is_active' => true,
                    'show_on_homepage' => true,
                    'sort_order' => $industry['sort_order'] ?? 0,
                ]
            );
        }
    }
}
