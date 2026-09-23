<?php

namespace App\Filament\Widgets;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use App\Models\ClinicalContent;
use App\Models\ContactEnquiry;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class OperationalStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $newBookings = BookingRequest::query()
            ->where('status', BookingRequestStatus::New->value)
            ->count();
        $openEnquiries = ContactEnquiry::query()->open()->count();
        $pendingTestimonials = Testimonial::query()
            ->where('status', Testimonial::STATUS_PENDING)
            ->count();
        $stats = [
            Stat::make('New booking requests', $newBookings)
                ->description('Review and respond')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning')
                ->url(route('filament.admin.resources.booking-requests.index')),
            Stat::make('Open contact enquiries', $openEnquiries)
                ->description('New or in progress')
                ->descriptionIcon('heroicon-m-inbox')
                ->color('info')
                ->url(route('filament.admin.resources.contact-enquiries.index')),
            Stat::make('Testimonials to review', $pendingTestimonials)
                ->description('Verify before publishing')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('gray')
                ->url(route('filament.admin.resources.testimonials.index')),
        ];

        if (auth()->user()?->is_clinical_reviewer === true) {
            $clinicalReviewQueue = ClinicalContent::query()
                ->whereIn('clinical_status', ['pending_review', 'changes_requested'])
                ->count();

            $stats[] = Stat::make('Clinical items in review', $clinicalReviewQueue)
                ->description('Approval remains gated')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('danger')
                ->url(route('filament.admin.resources.clinical-contents.index'));
        }

        return $stats;
    }
}
