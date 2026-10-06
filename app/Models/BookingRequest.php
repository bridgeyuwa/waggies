<?php

namespace App\Models;

use App\Enums\BookingRequestStatus;
use App\Support\BookingPricingCatalog;
use App\Support\BookingRequestCorrectionLogger;
use Database\Factories\BookingRequestFactory;
use DomainException;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class BookingRequest extends Model
{
    /** @use HasFactory<BookingRequestFactory> */
    use HasFactory, HasUuids, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'preferred_contact_method',
        'service_key',
        'service_variant',
        'pricing_tier',
        'source',
        'context',
        'requested_date',
        'requested_time',
        'pet_name',
        'pet_type',
        'location',
        'message',
        'status',
        'internal_notes',
        'quote_amount',
        'quote_currency',
        'quote_notes',
        'status_changed_at',
        'idempotency_key',
        'idempotency_hash',
    ];

    protected $attributes = [
        'status' => BookingRequestStatus::New->value,
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'status' => BookingRequestStatus::class,
            'context' => 'array',
            'quote_amount' => 'integer',
            'status_changed_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function serviceOptions(): array
    {
        return app(BookingPricingCatalog::class)->serviceOptions(availableOnly: true);
    }

    public function serviceLabel(): string
    {
        return self::serviceOptions()[$this->service_key] ?? Str::headline((string) $this->service_key);
    }

    /**
     * @return HasMany<BookingRequestPet, $this>
     */
    public function pets(): HasMany
    {
        return $this->hasMany(BookingRequestPet::class);
    }

    /**
     * @return HasMany<BookingRequestService, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(BookingRequestService::class);
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
            throw new DomainException("Booking request cannot transition from {$current->value} to {$status->value}.");
        }

        if ($status === BookingRequestStatus::Quoted && ! $this->servicesAreQuoteReady()) {
            throw new DomainException('A booking request needs a calculated amount or quote note before it can be marked quoted.');
        }

        if ($status === BookingRequestStatus::Confirmed && ! $this->canBeConfirmed()) {
            throw new DomainException('Booking request cannot be confirmed until every service is confirmed.');
        }

        if ($status === BookingRequestStatus::Completed && ! $this->canBeCompleted()) {
            throw new DomainException('Booking request cannot be completed until every service is completed.');
        }

        DB::transaction(function () use ($status): void {
            $this->setAttribute('status', $status);
            $this->status_changed_at = now();
            $this->save();

            if (in_array($status, [BookingRequestStatus::Declined, BookingRequestStatus::Cancelled], true)) {
                $this->services()
                    ->get()
                    ->each(function (BookingRequestService $service) use ($status): void {
                        $serviceStatus = $service->getAttribute('status');
                        $serviceStatus = $serviceStatus instanceof BookingRequestStatus
                            ? $serviceStatus
                            : BookingRequestStatus::from((string) $serviceStatus);

                        if (! $serviceStatus->isResolved()) {
                            $service->transitionTo($status);
                        }
                    });
            }
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

        return match ($status) {
            BookingRequestStatus::Quoted => $this->servicesAreQuoteReady(),
            BookingRequestStatus::Confirmed => $this->canBeConfirmed(),
            BookingRequestStatus::Completed => $this->canBeCompleted(),
            default => true,
        };
    }

    public function canBeConfirmed(): bool
    {
        return $this->services()->exists()
            && ! $this->services()
                ->where('status', '!=', BookingRequestStatus::Confirmed->value)
                ->exists();
    }

    public function hasQuoteDecision(): bool
    {
        return $this->servicesAreQuoteReady();
    }

    public function canBeCompleted(): bool
    {
        return $this->services()->exists()
            && ! $this->services()
                ->where('status', '!=', BookingRequestStatus::Completed->value)
                ->exists();
    }

    public function servicesAreQuoteReady(): bool
    {
        $services = $this->services()->get(['quote_amount', 'quote_currency', 'quote_notes']);

        return $services->isNotEmpty()
            && $services->every(fn (BookingRequestService $service): bool => $service->hasQuoteDecision());
    }

    public function syncMixedServiceOutcome(): void
    {
        $statuses = $this->services()->pluck('status');
        $hasConfirmedService = $statuses->contains(fn (mixed $status): bool => $this->statusValue($status) === BookingRequestStatus::Confirmed->value);
        $hasUnresolvedOrDeclinedService = $statuses->contains(function (mixed $status): bool {
            $statusValue = $this->statusValue($status);

            return $statusValue === BookingRequestStatus::Declined->value
                || $statusValue === BookingRequestStatus::Cancelled->value
                || ! BookingRequestStatus::tryFrom($statusValue)?->isResolved();
        });

        if (! $hasConfirmedService || ! $hasUnresolvedOrDeclinedService) {
            return;
        }

        $this->refresh();

        if ($this->statusValue($this->getAttribute('status')) !== BookingRequestStatus::AwaitingCustomer->value
            && $this->canTransitionTo(BookingRequestStatus::AwaitingCustomer)) {
            $this->transitionTo(BookingRequestStatus::AwaitingCustomer);
        }
    }

    public function syncQuoteSummary(): void
    {
        $services = $this->services()
            ->get(['quote_amount', 'quote_currency', 'quote_notes']);

        $amountsComplete = $services->isNotEmpty()
            && $services->every(fn (BookingRequestService $service): bool => $service->quote_amount !== null);
        $currencies = $services->pluck('quote_currency')->filter()->unique()->values();
        $notes = $this->quoteNotesSummary($services);

        $this->forceFill([
            'quote_amount' => $amountsComplete ? $services->sum('quote_amount') : null,
            'quote_currency' => $amountsComplete && $currencies->count() === 1 ? $currencies->first() : null,
            'quote_notes' => $notes,
        ]);

        if ($this->isDirty(['quote_amount', 'quote_currency', 'quote_notes'])) {
            $this->saveQuietly();
        }
    }

    public function syncLegacyServiceMessage(): void
    {
        $service = $this->services()->get()->first(
            function (BookingRequestService $service): bool {
                $details = $service->getAttribute('details');

                return is_array($details) && array_key_exists('message', $details);
            },
        );

        if (! $service instanceof BookingRequestService) {
            return;
        }

        $details = $service->getAttribute('details');
        $details = is_array($details) ? $details : [];

        if (($details['message'] ?? null) === $this->message) {
            return;
        }

        $oldDetails = $details;
        $details['message'] = $this->message;

        $service->forceFill(['details' => $details])->save();
        app(BookingRequestCorrectionLogger::class)->record(
            $service,
            'Customer message corrected',
            ['details' => $oldDetails],
            ['details' => $details],
        );
    }

    /**
     * @param  Collection<int, BookingRequestService>  $services
     */
    private function quoteNotesSummary(Collection $services): ?string
    {
        $notes = $services
            ->map(fn (BookingRequestService $service): ?string => filled($service->quote_notes)
                ? sprintf(
                    '%s: %s',
                    self::serviceOptions()[$service->service_key] ?? Str::headline((string) $service->service_key),
                    $service->quote_notes,
                )
                : null)
            ->filter()
            ->values();

        return $notes->isNotEmpty() ? $notes->implode(PHP_EOL) : null;
    }

    private function statusValue(mixed $status): string
    {
        return $status instanceof BookingRequestStatus ? $status->value : (string) $status;
    }
}
