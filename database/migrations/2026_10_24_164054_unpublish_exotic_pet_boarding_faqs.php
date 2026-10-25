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
            ->where('status', 'published')
            ->whereIn('question', [
                'Can you handle live feeding for my snake or reptile?',
                'Do you have experience with reptiles?',
                'What species do you NOT board?',
            ])
            ->update([
                'status' => 'draft',
                'published_at' => null,
                'updated_at' => now(),
            ]);
    }
};
