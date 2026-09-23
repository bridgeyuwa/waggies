<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'pgsql'
            || ! Schema::hasTable('clinical_contents')
            || Schema::getColumnType('clinical_contents', 'contentable_id') === 'uuid') {
            return;
        }

        if (DB::table('clinical_contents')->whereNotNull('contentable_id')->exists()) {
            throw new RuntimeException('Cannot convert populated clinical contentable IDs from integers to UUIDs without a verified mapping.');
        }

        Schema::getConnection()->statement(
            'alter table "clinical_contents" alter column "contentable_id" type uuid using null'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'pgsql'
            || ! Schema::hasTable('clinical_contents')
            || Schema::getColumnType('clinical_contents', 'contentable_id') !== 'uuid') {
            return;
        }

        if (DB::table('clinical_contents')->whereNotNull('contentable_id')->exists()) {
            throw new RuntimeException('Cannot reverse the clinical contentable UUID conversion while contentable IDs exist.');
        }

        Schema::getConnection()->statement(
            'alter table "clinical_contents" alter column "contentable_id" type bigint using null'
        );
    }
};
