<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $businessProfile = require database_path('seeders/fixtures/business_profile.php');

        DB::table('business_profiles')
            ->whereNull('map_url')
            ->update([
                'map_url' => $businessProfile['map_url'],
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        $businessProfile = require database_path('seeders/fixtures/business_profile.php');

        DB::table('business_profiles')
            ->where('map_url', $businessProfile['map_url'])
            ->update([
                'map_url' => null,
                'updated_at' => now(),
            ]);
    }
};
