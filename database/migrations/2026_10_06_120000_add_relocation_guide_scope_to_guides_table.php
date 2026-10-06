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
            $table->string('relocation_scope')->nullable()->after('relocation_direction');
            $table->string('route_label')->nullable()->after('relocation_scope');
            $table->index('relocation_scope', 'guides_relocation_scope_index');
        });

        $guidesFixture = require database_path('seeders/fixtures/guides.php');

        foreach ($guidesFixture['items'] ?? [] as $guide) {
            if (! filled($guide['relocationScope'] ?? null)) {
                continue;
            }

            DB::table('guides')
                ->where('slug', $guide['slug'])
                ->update([
                    'relocation_scope' => $guide['relocationScope'],
                    'route_label' => $guide['routeLabel'] ?? null,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table): void {
            $table->dropIndex('guides_relocation_scope_index');
            $table->dropColumn(['relocation_scope', 'route_label']);
        });
    }
};
