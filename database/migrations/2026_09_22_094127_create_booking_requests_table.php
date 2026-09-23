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
        Schema::create('booking_requests', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name', 120);
            $table->string('email', 255);
            $table->string('phone', 40);
            $table->string('service_key', 80);
            $table->date('requested_date');
            $table->time('requested_time')->nullable();
            $table->string('pet_name', 80);
            $table->string('pet_type', 40);
            $table->string('location', 255)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('service_key');
            $table->index('requested_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
