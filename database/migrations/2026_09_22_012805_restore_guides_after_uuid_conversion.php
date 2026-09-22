<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('guides_legacy') || DB::table('guides')->count() > 0) {
            return;
        }

        foreach (DB::table('guides_legacy')->get() as $guide) {
            $attributes = (array) $guide;
            unset($attributes['id']);
            $attributes['id'] = (string) Str::uuid7();
            DB::table('guides')->insert($attributes);
        }

        Schema::drop('guides_legacy');

        Schema::table('guides', function (Blueprint $table): void {
            $table->unique('slug', 'guides_slug_unique');
            $table->index('category', 'guides_category_index');
            $table->index('status', 'guides_status_index');
            $table->index('published_at', 'guides_published_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The source records are intentionally preserved during this forward-only conversion.
    }
};
