<?php

namespace App\Models;

use App\Enums\BookingRequestStatus;
use Database\Factories\BookingRequestFactory;
use DomainException;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BookingRequest extends Model
{
    /** @use HasFactory<BookingRequestFactory> */
    use HasFactory, HasUuids;

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
        return collect(config('waggies_pricing.services', []))
            ->mapWithKeys(static fn (array $service, string $key): array => [
                $key => $service['label'] ?? Str::headline($key),
            ])
            ->all();
    }

    public function serviceLabel(): string
    {
        return self::serviceOptions()[$this->service_key] ?? Str::headline((string) $this->service_key);
    }

    public function transitionTo(BookingRequestStatus $status): void
    {
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

        $this->setAttribute('status', $status);
        $this->status_changed_at = now();
        $this->save();
    }

    public function canTransitionTo(BookingRequestStatus $status): bool
    {
        $current = $this->getAttribute('status');
        $current = $current instanceof BookingRequestStatus
            ? $current
            : BookingRequestStatus::from((string) $current);

        return $current === $status || $current->canTransitionTo($status);
    }
}
