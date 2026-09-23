<?php

namespace App\Filament\Widgets;

use App\Models\ClinicalContent;
use App\Models\ContactEnquiry;
use App\Models\Guide;
use App\Models\JobOpening;
use App\Models\KnowledgeArticle;
use App\Models\Product;
use App\Models\Testimonial;
use Filament\Widgets\Widget;

final class DashboardWorkQueueWidget extends Widget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.dashboard-work-queue';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $queues = [
            [
                'label' => 'Contact enquiries',
                'count' => ContactEnquiry::query()->open()->count(),
                'description' => 'New or in-progress messages waiting for a response.',
                'href' => route('filament.admin.resources.contact-enquiries.index'),
                'tone' => 'info',
            ],
            [
                'label' => 'Pending testimonials',
                'count' => Testimonial::query()->where('status', Testimonial::STATUS_PENDING)->count(),
                'description' => 'Check consent, identity, CRM match, and customer relationship status.',
                'href' => route('filament.admin.resources.testimonials.index'),
                'tone' => 'warning',
            ],
            [
                'label' => 'Draft content',
                'count' => Guide::query()->where('status', Guide::STATUS_DRAFT)->count()
                    + KnowledgeArticle::query()->where('status', KnowledgeArticle::STATUS_DRAFT)->count()
                    + Product::query()->where('status', Product::STATUS_DRAFT)->count()
                    + JobOpening::query()->where('status', JobOpening::STATUS_DRAFT)->count(),
                'description' => 'Editorial and catalogue records not yet published.',
                'href' => route('filament.admin.resources.guides.index'),
                'tone' => 'gray',
            ],
        ];

        if (auth()->user()?->is_clinical_reviewer === true) {
            $queues[] = [
                'label' => 'Overdue clinical reviews',
                'count' => ClinicalContent::query()->whereNotNull('review_due_at')->where('review_due_at', '<', now())->count(),
                'description' => 'Keep stale clinical material unpublished until reviewed.',
                'href' => route('filament.admin.resources.clinical-contents.index'),
                'tone' => 'danger',
            ];
        }

        return [
            'queues' => $queues,
            'actions' => [
                ['label' => 'Review booking requests', 'href' => route('filament.admin.resources.booking-requests.index')],
                ['label' => 'Review contact enquiries', 'href' => route('filament.admin.resources.contact-enquiries.index')],
                ['label' => 'Add a product', 'href' => route('filament.admin.resources.products.create')],
                ['label' => 'Add a guide', 'href' => route('filament.admin.resources.guides.create')],
                ['label' => 'Manage job openings', 'href' => route('filament.admin.resources.job-openings.index')],
                ['label' => 'Edit business profile', 'href' => route('filament.admin.resources.business-profiles.index')],
            ],
        ];
    }
}
