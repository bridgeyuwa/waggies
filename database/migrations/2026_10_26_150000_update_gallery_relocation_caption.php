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
        DB::table('gallery_items')
            ->where('category', 'Relocation')
            ->where('caption', 'Pet Relocation & Transport')
            ->update(['caption' => 'Pet Relocation Coordination']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('gallery_items')
            ->where('category', 'Relocation')
            ->where('caption', 'Pet Relocation Coordination')
            ->update(['caption' => 'Pet Relocation & Transport']);
    }
};
