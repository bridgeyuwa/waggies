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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('rating');
            $table->string('service', 40);
            $table->string('title', 80);
            $table->text('story');
            $table->string('author_name', 60);
            $table->string('author_location', 80);
            $table->string('pet_name', 60)->nullable();
            $table->string('pet_type', 20);
            $table->string('photo_path')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('consented_at');
            $table->timestamp('published_at')->nullable();
            $table->index(['status', 'created_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
