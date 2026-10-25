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
            ->where('category', 'relocation')
            ->where('question', 'Do you provide airport pickup and drop-off for pets?')
            ->where('answer', 'Yes  -  we offer full door-to-door relocation including airport pickup/drop-off, customs clearance handling, and post-arrival monitoring.')
            ->update([
                'answer' => 'Airport support depends on the route. For imports into Abuja, Waggies can arrange airport pickup. For exports, Waggies coordinates transport to the departure airport. Your route-specific quote confirms available arrangements, providers, and costs. Waggies does not offer door-to-door delivery.',
                'updated_at' => now(),
            ]);

        DB::table('faqs')
            ->where('category', 'relocation')
            ->where('question', 'Can you handle door-to-door domestic relocation?')
            ->where('status', 'published')
            ->update([
                'status' => 'draft',
                'published_at' => null,
                'updated_at' => now(),
            ]);
    }
};
