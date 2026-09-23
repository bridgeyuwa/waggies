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

        $address = config('waggies.address', []);
        $socials = config('waggies.socials', []);

        DB::table('business_profiles')->insert([
            'business_name' => 'Waggies',
            'primary_email' => 'hello@waggies.ng',
            'phone' => config('waggies.phone'),
            'phone_international' => config('waggies.phone_international'),
            'whatsapp_url' => config('waggies.whatsapp'),
            'address_street' => $address['street'] ?? null,
            'address_city' => $address['city'] ?? null,
            'address_postal_code' => $address['postal_code'] ?? null,
            'address_state' => $address['state'] ?? null,
            'address_country' => $address['country'] ?? null,
            'timezone' => 'Africa/Lagos',
            'instagram_url' => $socials['instagram'] ?? null,
            'facebook_url' => $socials['facebook'] ?? null,
            'x_url' => $socials['x'] ?? null,
            'linkedin_url' => $socials['linkedin'] ?? null,
            'tiktok_url' => $socials['tiktok'] ?? null,
            'youtube_url' => $socials['youtube'] ?? null,
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
