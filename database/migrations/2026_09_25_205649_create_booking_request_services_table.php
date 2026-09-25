<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_request_services', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_request_id')->constrained()->cascadeOnDelete();
            $table->string('service_key', 80);
            $table->string('service_variant', 80)->nullable();
            $table->string('pricing_tier', 80)->nullable();
            $table->date('requested_date')->nullable();
            $table->time('requested_time')->nullable();
            $table->string('location', 255)->nullable();
            $table->json('details')->nullable();
            $table->string('status', 30)->default('pending');
            $table->unsignedBigInteger('quote_amount')->nullable();
            $table->string('quote_currency', 3)->nullable();
            $table->text('quote_notes')->nullable();
            $table->timestamp('status_changed_at')->nullable();
            $table->timestamps();

            $table->index(['service_key', 'status']);
            $table->index('requested_date');
        });

        $now = now();

        DB::table('booking_requests')
            ->select(['id', 'service_key', 'service_variant', 'pricing_tier', 'requested_date', 'requested_time', 'location', 'context', 'status', 'quote_amount', 'quote_currency', 'quote_notes', 'status_changed_at'])
            ->orderBy('created_at')
            ->each(function (object $request) use ($now): void {
                $serviceId = (string) Str::uuid();
                $petId = (string) Str::uuid();

                DB::table('booking_request_services')->insert([
                    'id' => $serviceId,
                    'booking_request_id' => $request->id,
                    'service_key' => $request->service_key,
                    'service_variant' => $request->service_variant,
                    'pricing_tier' => $request->pricing_tier,
                    'requested_date' => $request->requested_date,
                    'requested_time' => $request->requested_time,
                    'location' => $request->location,
                    'details' => json_encode(['legacy_context' => $request->context], JSON_THROW_ON_ERROR),
                    'status' => $request->status,
                    'quote_amount' => $request->quote_amount,
                    'quote_currency' => $request->quote_currency,
                    'quote_notes' => $request->quote_notes,
                    'status_changed_at' => $request->status_changed_at,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $parent = DB::table('booking_requests')->where('id', $request->id)->first(['pet_name', 'pet_type']);

                DB::table('booking_request_pets')->insert([
                    'id' => $petId,
                    'booking_request_id' => $request->id,
                    'name' => $parent->pet_name,
                    'species' => $parent->pet_type,
                    'details' => json_encode(['migrated_from_flat_request' => true], JSON_THROW_ON_ERROR),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_request_services');
    }
};
