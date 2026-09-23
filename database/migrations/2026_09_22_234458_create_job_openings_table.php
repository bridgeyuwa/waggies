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
        Schema::create('job_openings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title', 160);
            $table->string('department', 120)->nullable();
            $table->string('location', 160)->nullable();
            $table->string('employment_type', 80)->nullable();
            $table->string('summary', 500)->nullable();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->date('closing_date')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('application_email', 255)->nullable();
            $table->string('application_url', 500)->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['status', 'closing_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_openings');
    }
};
