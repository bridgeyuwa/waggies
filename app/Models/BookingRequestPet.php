<?php

namespace App\Models;

use Database\Factories\BookingRequestPetFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookingRequestPet extends Model
{
    /** @use HasFactory<BookingRequestPetFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'booking_request_id',
        'name',
        'species',
        'breed',
        'age',
        'sex',
        'notes',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
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
     * @return BelongsToMany<BookingRequestService, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(BookingRequestService::class, 'booking_request_service_pet');
    }
}
