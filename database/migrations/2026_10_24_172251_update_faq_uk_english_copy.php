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
            ->where('question', 'How do I know which service my pet needs?')
            ->update([
                'answer' => 'If you are unsure, start with a consultation or contact us. Our team will recommend the right service based on your pet’s age, health, and behaviour.',
                'updated_at' => now(),
            ]);
    }
};
