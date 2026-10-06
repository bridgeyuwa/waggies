<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guides', function (Blueprint $table): void {
            $table->string('relocation_direction')->nullable()->after('category');
            $table->char('origin_country_code', 2)->nullable()->after('relocation_direction');
            $table->char('destination_country_code', 2)->nullable()->after('origin_country_code');
            $table->date('last_reviewed_at')->nullable()->after('destination_country_code');
            $table->json('source_links')->nullable()->after('last_reviewed_at');
            $table->index(
                ['relocation_direction', 'origin_country_code', 'destination_country_code'],
                'guides_relocation_route_index',
            );
        });

        $guidesFixture = require database_path('seeders/fixtures/guides.php');

        foreach ($guidesFixture['items'] ?? [] as $guide) {
            if (! filled($guide['relocationDirection'] ?? null)) {
                continue;
            }

            DB::table('guides')
                ->where('slug', $guide['slug'])
                ->update([
                    'relocation_direction' => $guide['relocationDirection'],
                    'origin_country_code' => $guide['originCountryCode'],
                    'destination_country_code' => $guide['destinationCountryCode'],
                    'last_reviewed_at' => $guide['lastReviewedAt'],
                    'source_links' => json_encode($guide['sourceLinks']),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table): void {
            $table->dropIndex('guides_relocation_route_index');
            $table->dropColumn([
                'relocation_direction',
                'origin_country_code',
                'destination_country_code',
                'last_reviewed_at',
                'source_links',
            ]);
        });
    }
};
