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
        if (Schema::hasColumn('medications', 'clinical_content_id')) {
            Schema::table('medications', function (Blueprint $table): void {
                $table->dropColumn('clinical_content_id');
            });
        }

        Schema::dropIfExists('clinical_content_source');
        Schema::dropIfExists('clinical_reviews');
        Schema::dropIfExists('clinical_content_versions');
        Schema::dropIfExists('clinical_tool_reviews');
        Schema::dropIfExists('clinical_contents');
        Schema::dropIfExists('clinical_sources');

        if (Schema::hasColumn('users', 'is_clinical_reviewer')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('is_clinical_reviewer');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Governance records are intentionally not recreated during rollback.
    }
};
