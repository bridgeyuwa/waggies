<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class NewsletterSubscriber extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'newsletter_subscriptions';

    public const string STATUS_SUBSCRIBED = 'subscribed';

    public const string STATUS_UNSUBSCRIBED = 'unsubscribed';

    protected $attributes = [
        'status' => self::STATUS_SUBSCRIBED,
    ];

    protected $fillable = [
        'email',
        'status',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected static function booted(): void
    {
        self::saving(function (self $subscriber): void {
            $subscriber->email = self::normalizeEmail($subscriber->email);

            if (! in_array($subscriber->status, array_keys(self::statusOptions()), true)) {
                throw new InvalidArgumentException("Invalid newsletter subscriber status [{$subscriber->status}].");
            }

            if ($subscriber->status === self::STATUS_SUBSCRIBED) {
                $subscriber->subscribed_at ??= now();
                $subscriber->unsubscribed_at = null;

                return;
            }

            $subscriber->unsubscribed_at ??= now();
        });
    }

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public static function normalizeEmail(string $email): string
    {
        return mb_strtolower(trim($email));
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_SUBSCRIBED => 'Subscribed',
            self::STATUS_UNSUBSCRIBED => 'Unsubscribed',
        ];
    }
}
