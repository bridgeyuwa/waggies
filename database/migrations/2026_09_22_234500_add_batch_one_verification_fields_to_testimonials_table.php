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
            $table->string('contact_method', 20)->nullable()->after('author_location');
            $table->string('contact_value', 255)->nullable()->after('contact_method');
            $table->string('crm_match_status', 30)->default('not_checked')->after('contact_value');
            $table->string('identity_verification_status', 30)->default('not_checked')->after('crm_match_status');
            $table->string('customer_relationship_status', 30)->default('not_checked')->after('identity_verification_status');
            $table->string('suitecrm_record_id', 120)->nullable()->after('customer_relationship_status');
            $table->string('verification_method', 40)->nullable()->after('suitecrm_record_id');
            $table->timestamp('verified_at')->nullable()->after('verification_method');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable()->after('verified_by');
            $table->timestamp('moderated_at')->nullable()->after('verification_notes');
            $table->foreignId('moderated_by')->nullable()->after('moderated_at')->constrained('users')->nullOnDelete();

            $table->index(['crm_match_status', 'identity_verification_status', 'customer_relationship_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['moderated_by']);
            $table->dropIndex(['crm_match_status', 'identity_verification_status', 'customer_relationship_status']);
            $table->dropColumn([
                'contact_method',
                'contact_value',
                'crm_match_status',
                'identity_verification_status',
                'customer_relationship_status',
                'suitecrm_record_id',
                'verification_method',
                'verified_at',
                'verified_by',
                'verification_notes',
                'moderated_at',
                'moderated_by',
            ]);
        });
    }
};
