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
use Illuminate\Support\Facades\DB;
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
        $this->refresh();

        $current = $this->getAttribute('status');
        $current = $current instanceof BookingRequestStatus
            ? $current
            : BookingRequestStatus::from((string) $current);

        if ($current === $status) {
            return;
        }

        if (! $current->canTransitionTo($status)) {
            throw new DomainException("Booking request service cannot transition from {$current->value} to {$status->value}.");
        }

        if (in_array($status, [BookingRequestStatus::Quoted, BookingRequestStatus::Confirmed], true) && ! $this->hasQuoteDecision()) {
            throw new DomainException('A service needs a quote amount or quote note before it can be quoted or confirmed.');
        }

        DB::transaction(function () use ($status): void {
            $this->setAttribute('status', $status);
            $this->status_changed_at = now();
            $this->save();

            $this->bookingRequest()->first()?->syncMixedServiceOutcome();
        });
    }

    public function canTransitionTo(BookingRequestStatus $status): bool
    {
        $current = $this->getAttribute('status');
        $current = $current instanceof BookingRequestStatus
            ? $current
            : BookingRequestStatus::from((string) $current);

        if ($current === $status) {
            return true;
        }

        if (! $current->canTransitionTo($status)) {
            return false;
        }

        return ! in_array($status, [BookingRequestStatus::Quoted, BookingRequestStatus::Confirmed], true)
            || $this->hasQuoteDecision();
    }

    public function hasQuoteDecision(): bool
    {
        return ($this->quote_amount !== null && filled($this->quote_currency))
            || filled($this->quote_notes);
    }

    public function updateOperationalQuote(?int $amount, ?string $currency, ?string $notes): void
    {
        if ($amount !== null && $amount < 0) {
            throw new DomainException('A service quote amount cannot be negative.');
        }

        $currency = filled($currency) ? strtoupper(trim($currency)) : null;
        $notes = filled($notes) ? trim($notes) : null;

        if ($currency !== null && strlen($currency) > 3) {
            throw new DomainException('A service quote currency cannot exceed three characters.');
        }

        if ($amount === null && $notes === null) {
            throw new DomainException('A service quote needs an amount or quote notes before it can be saved.');
        }

        if ($amount !== null && $currency === null && $notes === null) {
            throw new DomainException('A service quote amount needs a currency unless quote notes explain the offer.');
        }

        $this->forceFill([
            'quote_amount' => $amount,
            'quote_currency' => $currency,
            'quote_notes' => $notes,
        ])->save();
    }
}
