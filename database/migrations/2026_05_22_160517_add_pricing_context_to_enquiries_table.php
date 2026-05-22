<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('service')->nullable()->after('email');
            $table->string('variant')->nullable()->after('service');
            $table->string('tier')->nullable()->after('variant');
            $table->string('intent')->nullable()->after('tier');
            $table->string('estimate_summary')->nullable()->after('intent');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['service', 'variant', 'tier', 'intent', 'estimate_summary']);
        });
    }
};
