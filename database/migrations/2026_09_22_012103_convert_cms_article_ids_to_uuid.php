<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Conversion is performed before the slug-history tables are created.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // UUID conversion is intentionally not reversed because it would destroy key relationships.
    }
};
