<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('industries')) {
            return;
        }

        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('description');
            $table->text('icon')->nullable();
            $table->json('tags')->nullable();
            $table->longText('overview')->nullable();
            $table->json('challenges')->nullable();
            $table->json('services')->nullable();
            $table->json('cybersecurity')->nullable();
            $table->json('technology')->nullable();
            $table->json('benefits')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_homepage')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'show_on_homepage', 'sort_order']);
        });

        // Seed the 20 preferred industries. Existing static sectors are not
        // touched: this table only stores the additive expansion records.
        $now = now();
        $rows = [];

        foreach (config('industries.new', []) as $industry) {
            $rows[] = [
                'name' => $industry['name'],
                'slug' => $industry['slug'],
                'category' => $industry['category'] ?? null,
                'description' => $industry['description'],
                'icon' => $industry['icon'] ?? null,
                'tags' => json_encode($industry['tags'] ?? [], JSON_UNESCAPED_UNICODE),
                'overview' => $industry['overview'] ?? null,
                'challenges' => json_encode($industry['challenges'] ?? [], JSON_UNESCAPED_UNICODE),
                'services' => json_encode($industry['services'] ?? [], JSON_UNESCAPED_UNICODE),
                'cybersecurity' => json_encode($industry['cybersecurity'] ?? [], JSON_UNESCAPED_UNICODE),
                'technology' => json_encode($industry['technology'] ?? [], JSON_UNESCAPED_UNICODE),
                'benefits' => json_encode($industry['benefits'] ?? [], JSON_UNESCAPED_UNICODE),
                'image_path' => '/images/industries/'.$industry['slug'].'.svg',
                'image_alt' => $industry['image_alt'] ?? null,
                'seo_title' => $industry['seo_title'] ?? null,
                'meta_description' => $industry['meta_description'] ?? null,
                'is_active' => true,
                'show_on_homepage' => true,
                'sort_order' => $industry['sort_order'] ?? 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows !== []) {
            DB::table('industries')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('industries');
    }
};
