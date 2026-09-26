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
        Schema::table('booking_request_services', function (Blueprint $table): void {
            $table->date('requested_end_date')->nullable()->after('requested_date');
            $table->json('price_snapshot')->nullable()->after('quote_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_request_services', function (Blueprint $table): void {
            $table->dropColumn(['requested_end_date', 'price_snapshot']);
        });
    }
};
