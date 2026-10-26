<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('guides')
            ->where('content', 'like', '%Abuja airport collection%')
            ->update([
                'content' => DB::raw("REPLACE(content, 'Abuja airport collection', 'collection at Nnamdi Azikiwe International Airport in Abuja')"),
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('guides')
            ->where('content', 'like', '%collection at Nnamdi Azikiwe International Airport in Abuja%')
            ->update([
                'content' => DB::raw("REPLACE(content, 'collection at Nnamdi Azikiwe International Airport in Abuja', 'Abuja airport collection')"),
                'updated_at' => now(),
            ]);
    }
};
