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
        Schema::create('newsletter_subscribers_batch_25', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('status', 20)->default('subscribed');
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'subscribed_at']);
        });

        $now = now();

        foreach (DB::table('newsletter_subscriptions')->orderBy('id')->get() as $subscriber) {
            DB::table('newsletter_subscribers_batch_25')->insert([
                'id' => (string) Str::uuid7(),
                'email' => mb_strtolower(trim($subscriber->email)),
                'status' => 'subscribed',
                'subscribed_at' => $subscriber->created_at ?? $now,
                'unsubscribed_at' => null,
                'created_at' => $subscriber->created_at ?? $now,
                'updated_at' => $subscriber->updated_at ?? $now,
            ]);
        }

        Schema::drop('newsletter_subscriptions');
        Schema::rename('newsletter_subscribers_batch_25', 'newsletter_subscriptions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriptions');
    }
};
