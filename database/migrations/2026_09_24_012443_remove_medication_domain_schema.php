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
        Schema::dropIfExists('medication_formulations');
        Schema::dropIfExists('medication_jurisdictions');
        Schema::dropIfExists('medications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Medication records are intentionally not recreated during rollback.
    }
};
