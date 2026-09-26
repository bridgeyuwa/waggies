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
        Schema::create('faqs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('category', 80);
            $table->string('subcategory', 80)->nullable();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'subcategory', 'sort_order']);
            $table->index(['status', 'published_at']);
        });

        $now = now();
        $faqsFixture = require database_path('seeders/fixtures/faqs.php');
        $faqs = array_map(
            static fn (array $faq): array => [
                'id' => (string) Str::uuid7(),
                'category' => $faq['category'],
                'subcategory' => $faq['subcategory'] ?? null,
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'sort_order' => $faq['id'],
                'status' => 'published',
                'published_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $faqsFixture,
        );

        if ($faqs !== []) {
            DB::table('faqs')->insert($faqs);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
