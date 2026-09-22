<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('service_prices');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The database-backed pricing authority is intentionally not recreated.
    }
};
