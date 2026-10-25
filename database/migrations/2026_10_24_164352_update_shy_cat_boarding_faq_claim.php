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
        DB::table('faqs')
            ->where('category', 'boarding')
            ->where('question', 'My cat is very shy  -  will she be okay?')
            ->update([
                'answer' => 'If your cat is shy, please share their settling and handling needs in your request. Staff will review them before confirming the stay.',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not restore unsupported shy-cat handling claims.
    }
};
