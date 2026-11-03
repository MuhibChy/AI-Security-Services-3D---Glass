<?php

namespace App\Support;

use App\Models\Industry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Single read path for the 20 additive preferred industries.
 *
 * The database is authoritative once migrated. The config file is a safe
 * fallback so the public site never breaks before `php artisan migrate`
 * (or during a fresh clone) and all 20 industries always render.
 */
class IndustryCatalogue
{
    public static function all(bool $homepageOnly = false): Collection
    {
        if (static::hasTable()) {
            $query = Industry::query()->active()->ordered();

            if ($homepageOnly) {
                $query->onHomepage();
            }

            $records = $query->get();

            if ($records->isNotEmpty()) {
                return $records->map(fn (Industry $industry) => $industry->toPublicArray());
            }
        }

        return static::fromConfig($homepageOnly);
    }

    public static function find(string $slug): ?array
    {
        if (static::hasTable()) {
            $industry = Industry::query()->active()->where('slug', $slug)->first();

            if ($industry) {
                return $industry->toPublicArray();
            }

            return null;
        }

        return static::fromConfig()->firstWhere('slug', $slug);
    }

    public static function slugs(): Collection
    {
        return static::all()->pluck('slug')->filter()->values();
    }

    protected static function fromConfig(bool $homepageOnly = false): Collection
    {
        return collect(config('industries.new', []))
            ->when($homepageOnly, fn (Collection $items) => $items->where('show_on_homepage', true))
            ->sortBy('sort_order')
            ->values()
            ->map(function (array $industry) {
                return [
                    'id' => null,
                    'name' => $industry['name'],
                    'slug' => $industry['slug'],
                    'category' => $industry['category'] ?? null,
                    'description' => $industry['description'] ?? '',
                    'icon' => $industry['icon'] ?? null,
                    'tags' => $industry['tags'] ?? [],
                    'overview' => $industry['overview'] ?? null,
                    'challenges' => $industry['challenges'] ?? [],
                    'services' => $industry['services'] ?? [],
                    'cybersecurity' => $industry['cybersecurity'] ?? [],
                    'technology' => $industry['technology'] ?? [],
                    'benefits' => $industry['benefits'] ?? [],
                    'image_url' => '/images/industries/'.$industry['slug'].'.svg',
                    'image_alt' => $industry['image_alt'] ?? $industry['name'],
                    'seo_title' => $industry['seo_title'] ?? null,
                    'meta_description' => $industry['meta_description'] ?? null,
                    'sort_order' => $industry['sort_order'] ?? 0,
                    'created_at' => null,
                ];
            });
    }

    protected static function hasTable(): bool
    {
        try {
            return Schema::hasTable('industries');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
