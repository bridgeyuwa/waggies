<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['moderated_by']);
            $table->dropColumn([
                'title',
                'pet_name',
                'pet_type',
                'photo_path',
                'verification_method',
                'verified_at',
                'verified_by',
                'verification_notes',
                'moderated_at',
                'moderated_by',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->string('title', 120)->nullable();
            $table->string('pet_name', 60)->nullable();
            $table->string('pet_type', 40)->nullable();
            $table->string('photo_path')->nullable();
            $table->string('verification_method', 40)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }
};
