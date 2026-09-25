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
        Schema::create('booking_request_service_pet', function (Blueprint $table): void {
            $table->foreignUuid('booking_request_service_id')->constrained('booking_request_services')->cascadeOnDelete();
            $table->foreignUuid('booking_request_pet_id')->constrained('booking_request_pets')->cascadeOnDelete();

            $table->primary(['booking_request_service_id', 'booking_request_pet_id']);
        });

        DB::table('booking_request_services as services')
            ->join('booking_request_pets as pets', 'pets.booking_request_id', '=', 'services.booking_request_id')
            ->select(['services.id as service_id', 'pets.id as pet_id'])
            ->orderBy('services.id')
            ->each(function (object $link): void {
                DB::table('booking_request_service_pet')->insert([
                    'booking_request_service_id' => $link->service_id,
                    'booking_request_pet_id' => $link->pet_id,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_request_service_pet');
    }
};
