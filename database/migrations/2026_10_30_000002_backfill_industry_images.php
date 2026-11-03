<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Backfill the shipped SVG artwork for industry records created before the
 * images were added. Only null image paths are touched — admin uploads and
 * custom paths are left exactly as they are.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('industries')) {
            return;
        }

        $rows = DB::table('industries')->whereNull('image_path')->get(['id', 'slug']);

        foreach ($rows as $row) {
            $path = '/images/industries/'.$row->slug.'.svg';

            if (file_exists(public_path(ltrim($path, '/')))) {
                DB::table('industries')->where('id', $row->id)->update(['image_path' => $path]);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('industries')) {
            return;
        }

        DB::table('industries')
            ->where('image_path', 'like', '/images/industries/%')
            ->update(['image_path' => null]);
    }
};
