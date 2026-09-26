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
        DB::table('job_openings')
            ->whereIn('status', ['closed', 'archived'])
            ->update(['status' => 'draft']);

        Schema::table('job_openings', function (Blueprint $table): void {
            $table->dropIndex(['status', 'closing_date']);
            $table->dropColumn(['closing_date', 'application_email', 'application_url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_openings', function (Blueprint $table): void {
            $table->date('closing_date')->nullable();
            $table->string('application_email', 255)->nullable();
            $table->string('application_url', 500)->nullable();
            $table->index(['status', 'closing_date']);
        });
    }
};
