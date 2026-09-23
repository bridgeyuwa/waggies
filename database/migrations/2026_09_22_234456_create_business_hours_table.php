<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('business_hours', function (Blueprint $table): void {
            $table->id();
            $table->string('kind', 20)->default('weekly');
            $table->unsignedTinyInteger('day_of_week')->nullable();
            $table->date('date')->nullable();
            $table->string('label', 160)->nullable();
            $table->boolean('is_closed')->default(false);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->time('second_open_time')->nullable();
            $table->time('second_close_time')->nullable();
            $table->timestamps();

            $table->index(['kind', 'day_of_week']);
            $table->index(['kind', 'date']);
        });

        foreach ([
            [1, '09:00', '17:00'], [2, '09:00', '17:00'], [3, '09:00', '17:00'],
            [4, '09:00', '17:00'], [5, '09:00', '17:00'], [6, '10:00', '14:00'],
            [0, '10:00', '14:00'],
        ] as [$day, $open, $close]) {
            DB::table('business_hours')->insert([
                'kind' => 'weekly',
                'day_of_week' => $day,
                'is_closed' => false,
                'open_time' => $open,
                'close_time' => $close,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_hours');
    }
};
