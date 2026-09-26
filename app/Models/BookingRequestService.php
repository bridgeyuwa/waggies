<?php

namespace App\Models;

use Database\Factories\BookingRequestServiceFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookingRequestService extends Model
{
    /** @use HasFactory<BookingRequestServiceFactory> */
    use HasFactory, HasUuids;

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
            'quote_amount' => 'integer',
            'price_snapshot' => 'array',
            'status_changed_at' => 'datetime',
        ];
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
}
