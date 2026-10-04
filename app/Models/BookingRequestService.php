<?php

namespace App\Models;

use App\Enums\BookingRequestStatus;
use Database\Factories\BookingRequestServiceFactory;
use DomainException;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class BookingRequestService extends Model
{
    /** @use HasFactory<BookingRequestServiceFactory> */
    use HasFactory, HasUuids, LogsActivity;

    protected $fillable = [
        'booking_request_id',
        'service_key',
        'service_variant',
        'pricing_tier',
        'requested_date',
        'requested_end_date',
        'requested_time',
        'location',
        'details',
        'status',
        'quote_amount',
        'quote_currency',
        'quote_notes',
        'price_snapshot',
        'status_changed_at',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'requested_end_date' => 'date',
            'details' => 'array',
            'status' => BookingRequestStatus::class,
            'quote_amount' => 'integer',
            'price_snapshot' => 'array',
            'status_changed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $service): void {
            if ($service->wasChanged(['quote_amount', 'quote_currency', 'quote_notes'])) {
                $service->bookingRequest()->first()?->syncQuoteSummary();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('booking-requests')
            ->logOnly([
                'status',
                'status_changed_at',
                'quote_amount',
                'quote_currency',
                'quote_notes',
            ])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    /**
     * @return BelongsTo<BookingRequest, $this>
     */
    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    /**
     * @return BelongsToMany<BookingRequestPet, $this>
     */
    public function pets(): BelongsToMany
    {
        return $this->belongsToMany(BookingRequestPet::class, 'booking_request_service_pet');
    }

    public function transitionTo(BookingRequestStatus $status): void
    {
        $current = $this->status instanceof BookingRequestStatus
            ? $this->status
            : BookingRequestStatus::from((string) $this->status);

        if ($current === $status) {
            return;
        }

        if (! $current->canTransitionTo($status)) {
            throw new DomainException("Booking request service cannot transition from {$current->value} to {$status->value}.");
        }

        if (in_array($status, [BookingRequestStatus::Quoted, BookingRequestStatus::Confirmed], true) && ! $this->hasQuoteDecision()) {
            throw new DomainException('A service needs a quote amount or quote note before it can be quoted or confirmed.');
        }

        $this->setAttribute('status', $status);
        $this->status_changed_at = now();
        $this->save();
    }

    public function canTransitionTo(BookingRequestStatus $status): bool
    {
        $current = $this->status instanceof BookingRequestStatus
            ? $this->status
            : BookingRequestStatus::from((string) $this->status);

        return $current === $status || $current->canTransitionTo($status);
    }

    public function hasQuoteDecision(): bool
    {
        return $this->quote_amount !== null || filled($this->quote_notes);
    }
}
