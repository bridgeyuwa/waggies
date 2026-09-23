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
        Schema::table('booking_requests', function (Blueprint $table): void {
            $table->string('preferred_contact_method', 30)->nullable()->after('phone');
            $table->string('service_variant', 80)->nullable()->after('service_key');
            $table->string('pricing_tier', 80)->nullable()->after('service_variant');
            $table->string('source', 120)->nullable()->after('pricing_tier');
            $table->json('context')->nullable()->after('source');
            $table->text('internal_notes')->nullable()->after('message');
            $table->unsignedBigInteger('quote_amount')->nullable()->after('internal_notes');
            $table->string('quote_currency', 3)->nullable()->after('quote_amount');
            $table->text('quote_notes')->nullable()->after('quote_currency');
            $table->timestamp('status_changed_at')->nullable()->after('quote_notes');

            $table->index(['service_key', 'status']);
        });

        DB::table('booking_requests')->where('status', 'new')->update(['status' => 'pending']);
        DB::table('booking_requests')->where('status', 'contacted')->update(['status' => 'reviewing']);
        DB::table('booking_requests')->where('status', 'completed')->update(['status' => 'confirmed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table): void {
            $table->dropIndex(['service_key', 'status']);
            $table->dropColumn([
                'preferred_contact_method',
                'service_variant',
                'pricing_tier',
                'source',
                'context',
                'internal_notes',
                'quote_amount',
                'quote_currency',
                'quote_notes',
                'status_changed_at',
            ]);
        });
    }
};
