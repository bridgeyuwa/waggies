<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_request_pets', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_request_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->string('species', 40);
            $table->string('breed', 120)->nullable();
            $table->string('age', 40)->nullable();
            $table->string('sex', 30)->nullable();
            $table->text('notes')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->index('booking_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_request_pets');
    }
};
