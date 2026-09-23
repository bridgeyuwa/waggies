<?php

namespace Tests\Feature;

use App\Actions\ClinicalContentWorkflow;
use App\Enums\ClinicalContentStatus;
use App\Enums\ClinicalPublicationStatus;
use App\Models\ClinicalContent;
use App\Models\ClinicalReview;
use App\Models\ClinicalSource;
use App\Models\Guide;
use App\Models\Medication;
use App\Models\User;
use App\Support\ClinicalPublicationGate;
use Tests\TestCase;

class ClinicalGovernanceTest extends TestCase
{
    public function test_unreviewed_high_risk_content_cannot_be_published(): void
    {
        $content = ClinicalContent::create([
            'content_key' => 'triage-unreviewed',
            'content_type' => 'clinical',
            'clinical_status' => ClinicalContentStatus::PendingReview,
            'publication_status' => ClinicalPublicationStatus::Unpublished,
            'risk_level' => 'critical',
            'jurisdiction' => 'Global',
        ]);

        $this->expectException(\LogicException::class);
        $content->update(['publication_status' => ClinicalPublicationStatus::Published]);
    }

    public function test_ordinary_editors_cannot_create_an_approval_review(): void
    {
        $content = ClinicalContent::create([
            'content_key' => 'ordinary-editor-review',
            'content_type' => 'clinical',
            'clinical_status' => 'pending_review',
            'publication_status' => 'unpublished',
            'risk_level' => 'high',
            'jurisdiction' => 'Global',
        ]);

        $this->expectException(\LogicException::class);
        ClinicalReview::create([
            'clinical_content_id' => $content->id,
            'reviewer_id' => User::factory()->create(['is_clinical_reviewer' => false])->id,
            'decision' => 'approved',
            'content_version' => 1,
        ]);
    }

    public function test_approved_current_version_with_source_can_be_published(): void
    {
        $reviewer = User::factory()->create(['is_clinical_reviewer' => true]);
        $source = ClinicalSource::create([
            'title' => 'Authoritative veterinary reference',
            'organization' => 'Evidence authority',
            'source_type' => 'clinical_guideline',
            'reference' => 'https://example.test/reference',
            'jurisdiction' => 'Global',
            'status' => 'active',
        ]);
        $content = ClinicalContent::create([
            'content_key' => 'nutrition-reviewed',
            'content_type' => 'clinical',
            'clinical_status' => 'pending_review',
            'publication_status' => 'unpublished',
            'risk_level' => 'moderate',
            'jurisdiction' => 'Global',
            'version' => 1,
        ]);
        $content->sources()->attach($source, ['purpose' => 'primary evidence', 'source_version' => '1']);
        ClinicalReview::create([
            'clinical_content_id' => $content->id,
            'clinical_source_id' => $source->id,
            'reviewer_id' => $reviewer->id,
            'reviewer_name' => 'Actual reviewer supplied by business',
            'reviewer_credential' => 'Credential supplied by business',
            'decision' => 'approved',
            'reviewed_at' => now(),
            'next_review_at' => now()->addMonths(6),
            'content_version' => 1,
            'source_version' => '1',
        ]);

        $content->update(['clinical_status' => ClinicalContentStatus::Approved]);
        $content->update(['publication_status' => ClinicalPublicationStatus::Published]);

        expect(app(ClinicalPublicationGate::class)->allows($content->fresh()))->toBeTrue();
        expect($content->fresh()->isPubliclyEligible())->toBeTrue();
    }

    public function test_clinical_workflow_creates_approval_review_before_publication(): void
    {
        $reviewer = User::factory()->create(['is_clinical_reviewer' => true]);
        $source = ClinicalSource::create([
            'title' => 'Workflow source',
            'organization' => 'Evidence authority',
            'source_type' => 'clinical_guideline',
            'reference' => 'https://example.test/workflow-source',
            'jurisdiction' => 'Global',
            'status' => 'active',
            'version' => '1',
        ]);
        $content = ClinicalContent::create([
            'content_key' => 'workflow-content',
            'content_type' => 'clinical',
            'clinical_status' => 'pending_review',
            'publication_status' => 'unpublished',
            'risk_level' => 'moderate',
            'jurisdiction' => 'Global',
            'version' => 1,
        ]);
        $content->sources()->attach($source, ['source_version' => '1']);

        $review = app(ClinicalContentWorkflow::class)->approve($content, $reviewer);

        $this->assertDatabaseHas('clinical_reviews', [
            'id' => $review->id,
            'clinical_content_id' => $content->id,
            'reviewer_id' => $reviewer->id,
            'decision' => 'approved',
            'content_version' => 1,
        ]);
        $this->assertDatabaseHas('clinical_contents', [
            'id' => $content->id,
            'clinical_status' => 'approved',
            'publication_status' => 'unpublished',
        ]);

        app(ClinicalContentWorkflow::class)->publish($content->fresh());

        $this->assertDatabaseHas('clinical_contents', [
            'id' => $content->id,
            'clinical_status' => 'approved',
            'publication_status' => 'published',
        ]);
    }

    public function test_ordinary_staff_cannot_open_internal_clinical_resources(): void
    {
        $this->actingAs(User::factory()->create(['is_clinical_reviewer' => false]));

        $this->get('/admin/clinical-contents')->assertForbidden();
        $this->get('/admin/clinical-reviews')->assertForbidden();
        $this->get('/admin/clinical-sources')->assertForbidden();
        $this->get('/admin/clinical-tool-reviews')->assertForbidden();
    }

    public function test_clinical_reviewer_can_open_governance_records_but_not_tool_reviews(): void
    {
        $this->actingAs(User::factory()->create(['is_clinical_reviewer' => true]));

        $this->get('/admin/clinical-contents')->assertOk();
        $this->get('/admin/clinical-reviews')
            ->assertOk()
            ->assertDontSee('Create clinical review');
        $this->get('/admin/clinical-sources')->assertOk();
        $this->get('/admin/clinical-tool-reviews')->assertForbidden();
    }

    public function test_source_conflict_and_withdrawal_remove_public_eligibility(): void
    {
        $content = ClinicalContent::create([
            'content_key' => 'withdrawn-tool',
            'content_type' => 'clinical',
            'clinical_status' => 'pending_review',
            'publication_status' => 'unpublished',
            'risk_level' => 'high',
            'jurisdiction' => 'Nigeria',
            'source_conflict' => true,
        ]);

        expect(app(ClinicalPublicationGate::class)->allows($content))->toBeFalse();

        $content->withdraw('Source changed; clinical review required.', User::factory()->create(['is_clinical_reviewer' => true]));

        expect($content->fresh()->clinical_status)->toBe(ClinicalContentStatus::Withdrawn)
            ->and($content->fresh()->isPubliclyEligible())->toBeFalse();
    }

    public function test_clinical_guides_are_excluded_from_search_until_governed(): void
    {
        $guide = Guide::query()->firstOrFail();
        ClinicalContent::create([
            'content_key' => 'guide-'.$guide->getKey(),
            'contentable_type' => Guide::class,
            'contentable_id' => $guide->getKey(),
            'content_type' => 'clinical',
            'clinical_status' => 'pending_review',
            'publication_status' => 'unpublished',
            'risk_level' => 'high',
            'jurisdiction' => 'Nigeria',
        ]);

        expect($guide->fresh()->isIndexable())->toBeFalse();
    }

    public function test_dose_display_fails_closed_for_unreviewed_medication(): void
    {
        $medication = Medication::create([
            'generic_name' => 'Example medicine',
            'classification' => 'unknown',
            'species' => ['dog'],
        ]);
        $formulation = $medication->formulations()->create([
            'formulation' => 'liquid',
            'route' => 'oral',
            'concentration' => 10,
            'concentration_unit' => 'mg/mL',
            'dose_data' => ['basis' => 'withheld pending review'],
            'dose_display_enabled' => true,
        ]);

        expect($formulation->canDisplayDose())->toBeFalse();
    }
}
