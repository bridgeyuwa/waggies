<?php

namespace App\Models;

use App\Enums\BookingRequestStatus;
use Database\Factories\BookingRequestFactory;
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
        'service_key',
        'requested_date',
        'requested_time',
        'pet_name',
        'pet_type',
        'location',
        'message',
        'status',
    ];

    protected $attributes = [
        'status' => BookingRequestStatus::New->value,
    ];

    protected function casts(): array
    {
        return [
            'requested_date' => 'date',
            'status' => BookingRequestStatus::class,
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
}
