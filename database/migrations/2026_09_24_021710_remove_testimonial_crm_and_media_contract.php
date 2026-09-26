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
            $table->dropIndex(['crm_match_status', 'identity_verification_status', 'customer_relationship_status']);
            $table->dropColumn([
                'contact_method',
                'contact_value',
                'crm_match_status',
                'identity_verification_status',
                'customer_relationship_status',
                'suitecrm_record_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->string('contact_method', 20)->nullable();
            $table->string('contact_value', 255)->nullable();
            $table->string('crm_match_status', 30)->default('not_checked');
            $table->string('identity_verification_status', 30)->default('not_checked');
            $table->string('customer_relationship_status', 30)->default('not_checked');
            $table->string('suitecrm_record_id', 120)->nullable();

            $table->index(['crm_match_status', 'identity_verification_status', 'customer_relationship_status']);
        });
    }
};
