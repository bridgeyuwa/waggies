<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_sources', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('organization')->nullable();
            $table->string('author')->nullable();
            $table->string('source_type', 80);
            $table->string('reference', 2048);
            $table->string('jurisdiction', 80);
            $table->json('species')->nullable();
            $table->string('topic')->nullable();
            $table->date('published_on')->nullable();
            $table->date('revised_on')->nullable();
            $table->timestamp('accessed_at')->nullable();
            $table->string('version')->nullable();
            $table->string('identifier')->nullable();
            $table->string('evidence_level', 40)->nullable();
            $table->string('status', 40)->default('active');
            $table->text('notes')->nullable();
            $table->string('source_hash', 64)->nullable();
            $table->boolean('has_conflict')->default(false);
            $table->timestamps();
            $table->index(['jurisdiction', 'source_type']);
            $table->index(['status', 'has_conflict']);
        });

        Schema::create('clinical_contents', function (Blueprint $table): void {
            $table->id();
            $table->string('content_key')->unique();
            $table->nullableMorphs('contentable');
            $table->string('content_type', 30)->default('clinical');
            $table->string('clinical_status', 40)->default('draft');
            $table->string('publication_status', 40)->default('unpublished');
            $table->string('risk_level', 30)->default('moderate');
            $table->string('jurisdiction', 80)->default('Global');
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('review_due_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->foreignId('withdrawn_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('withdrawal_reason')->nullable();
            $table->boolean('source_conflict')->default(false);
            $table->text('conflict_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['clinical_status', 'publication_status', 'risk_level']);
            $table->index(['review_due_at', 'withdrawn_at']);
        });

        Schema::create('clinical_content_source', function (Blueprint $table): void {
            $table->foreignId('clinical_content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinical_source_id')->constrained()->restrictOnDelete();
            $table->string('purpose')->nullable();
            $table->string('source_version')->nullable();
            $table->timestamps();
            $table->primary(['clinical_content_id', 'clinical_source_id']);
        });

        Schema::create('clinical_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('clinical_content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinical_source_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_credential')->nullable();
            $table->string('organization')->nullable();
            $table->string('review_reference')->nullable();
            $table->string('decision', 40);
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('next_review_at')->nullable();
            $table->unsignedInteger('content_version');
            $table->string('source_version')->nullable();
            $table->timestamps();
            $table->index(['clinical_content_id', 'decision', 'content_version']);
        });

        Schema::create('clinical_content_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('clinical_content_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->json('snapshot');
            $table->text('change_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->unique(['clinical_content_id', 'version']);
        });

        Schema::create('medications', function (Blueprint $table): void {
            $table->id();
            $table->string('generic_name');
            $table->string('brand_name')->nullable();
            $table->string('manufacturer')->nullable();
            $table->json('active_ingredients')->nullable();
            $table->string('classification', 60)->default('unknown');
            $table->json('species')->nullable();
            $table->json('indications')->nullable();
            $table->json('routes')->nullable();
            $table->json('warnings')->nullable();
            $table->json('contraindications')->nullable();
            $table->json('interactions')->nullable();
            $table->foreignId('clinical_content_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index(['generic_name', 'classification']);
        });

        Schema::create('medication_formulations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('medication_id')->constrained()->cascadeOnDelete();
            $table->string('formulation');
            $table->string('route');
            $table->decimal('strength', 12, 4)->nullable();
            $table->string('strength_unit', 40)->nullable();
            $table->decimal('concentration', 12, 4)->nullable();
            $table->string('concentration_unit', 40)->nullable();
            $table->json('combination_ingredients')->nullable();
            $table->json('dose_data')->nullable();
            $table->boolean('dose_display_enabled')->default(false);
            $table->timestamps();
        });

        Schema::create('medication_jurisdictions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('medication_id')->constrained()->cascadeOnDelete();
            $table->string('jurisdiction', 80);
            $table->string('registration_number')->nullable();
            $table->string('registration_status', 50)->default('unknown');
            $table->string('product_name')->nullable();
            $table->string('label_reference', 2048)->nullable();
            $table->date('approval_date')->nullable();
            $table->string('applicant')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['medication_id', 'jurisdiction']);
        });

        Schema::create('clinical_tool_reviews', function (Blueprint $table): void {
            $table->id();
            $table->string('tool_key')->unique();
            $table->string('clinical_status', 40)->default('draft');
            $table->string('risk_level', 30)->default('moderate');
            $table->string('jurisdiction', 80)->default('Global');
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('clinical_source_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('review_due_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_tool_reviews');
        Schema::dropIfExists('medication_jurisdictions');
        Schema::dropIfExists('medication_formulations');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('clinical_content_versions');
        Schema::dropIfExists('clinical_reviews');
        Schema::dropIfExists('clinical_content_source');
        Schema::dropIfExists('clinical_contents');
        Schema::dropIfExists('clinical_sources');
    }
};
