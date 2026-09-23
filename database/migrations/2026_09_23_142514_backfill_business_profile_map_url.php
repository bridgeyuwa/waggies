<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('business_profiles')
            ->whereNull('map_url')
            ->update([
                'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3939.758!2d7.4913!3d9.0579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOcKwMDMnMzQuNCJOIDfCsDI5JzI4LjciRQ!5e0!3m2!1sen!2sng!4v1',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('business_profiles')
            ->where('map_url', config('waggies.map_url'))
            ->update([
                'map_url' => null,
                'updated_at' => now(),
            ]);
    }
};
