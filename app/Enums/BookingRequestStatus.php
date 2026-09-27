<?php

namespace App\Enums;

enum BookingRequestStatus: string
{
    case New = 'pending';

    case Reviewing = 'reviewing';

    case AwaitingCustomer = 'awaiting_customer';

    case Quoted = 'quoted';

    case Confirmed = 'confirmed';

    case Declined = 'declined';

    case Cancelled = 'cancelled';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::New->value => 'Pending',
            self::Reviewing->value => 'Reviewing',
            self::AwaitingCustomer->value => 'Awaiting customer',
            self::Quoted->value => 'Quoted',
            self::Confirmed->value => 'Confirmed',
            self::Declined->value => 'Declined',
            self::Cancelled->value => 'Cancelled',
        ];
    }

    public function canTransitionTo(self $status): bool
    {
        return in_array($status, match ($this) {
            self::New => [self::Reviewing, self::Declined, self::Cancelled],
            self::Reviewing => [self::AwaitingCustomer, self::Quoted, self::Confirmed, self::Declined, self::Cancelled],
            self::AwaitingCustomer => [self::Reviewing, self::Quoted, self::Confirmed, self::Declined, self::Cancelled],
            self::Quoted => [self::AwaitingCustomer, self::Confirmed, self::Declined, self::Cancelled],
            self::Confirmed => [self::Cancelled],
            self::Declined => [],
            self::Cancelled => [],
        }, true);
    }
}
