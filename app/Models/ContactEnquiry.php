<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class ContactEnquiry extends Model
{
    use HasFactory;
    use HasUuids;

    public const string STATUS_NEW = 'new';

    public const string STATUS_IN_PROGRESS = 'in_progress';

    public const string STATUS_RESOLVED = 'resolved';

    public const string STATUS_SPAM = 'spam';

    protected $attributes = [
        'status' => self::STATUS_NEW,
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'service',
        'intent',
        'message',
        'reference',
        'status',
        'received_at',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $enquiry): void {
            if (! in_array($enquiry->status, array_keys(self::statusOptions()), true)) {
                throw new InvalidArgumentException("Invalid contact enquiry status [{$enquiry->status}].");
            }

            $enquiry->received_at ??= now();
        });
    }

    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_IN_PROGRESS]);
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_IN_PROGRESS => 'In progress',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_SPAM => 'Spam',
        ];
    }
}
