<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('business_profiles', function (Blueprint $table): void {
            $table->id();
            $table->string('business_name', 160);
            $table->string('primary_email', 255);
            $table->string('phone', 40);
            $table->string('phone_international', 40)->nullable();
            $table->string('whatsapp_url', 255)->nullable();
            $table->string('address_street', 255)->nullable();
            $table->string('address_city', 120)->nullable();
            $table->string('address_postal_code', 20)->nullable();
            $table->string('address_state', 120)->nullable();
            $table->string('address_country', 120)->nullable();
            $table->string('map_url', 500)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('timezone', 80)->default('Africa/Lagos');
            $table->string('instagram_url', 255)->nullable();
            $table->string('facebook_url', 255)->nullable();
            $table->string('x_url', 255)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->string('tiktok_url', 255)->nullable();
            $table->string('youtube_url', 255)->nullable();
            $table->timestamps();
        });

        $businessProfile = require database_path('seeders/fixtures/business_profile.php');

        DB::table('business_profiles')->insert([
            'business_name' => $businessProfile['business_name'],
            'primary_email' => $businessProfile['primary_email'],
            'phone' => $businessProfile['phone'],
            'phone_international' => $businessProfile['phone_international'],
            'whatsapp_url' => $businessProfile['whatsapp_url'],
            'address_street' => $businessProfile['address']['street'],
            'address_city' => $businessProfile['address']['city'],
            'address_postal_code' => $businessProfile['address']['postal_code'],
            'address_state' => $businessProfile['address']['state'],
            'address_country' => $businessProfile['address']['country'],
            'timezone' => $businessProfile['timezone'],
            'map_url' => $businessProfile['map_url'],
            'instagram_url' => $businessProfile['socials']['instagram'],
            'facebook_url' => $businessProfile['socials']['facebook'],
            'x_url' => $businessProfile['socials']['x'],
            'linkedin_url' => $businessProfile['socials']['linkedin'],
            'tiktok_url' => $businessProfile['socials']['tiktok'],
            'youtube_url' => $businessProfile['socials']['youtube'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
