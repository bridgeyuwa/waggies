<?php

namespace App\Enums;

enum BookingRequestStatus: string
{
    case New = 'pending';

    case Reviewing = 'reviewing';

    case AwaitingCustomer = 'awaiting_customer';

    case Quoted = 'quoted';

    case Confirmed = 'confirmed';

    case Completed = 'completed';

    case Declined = 'declined';

    case Cancelled = 'cancelled';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::New->value => 'Request received',
            self::Reviewing->value => 'Under review',
            self::AwaitingCustomer->value => 'Awaiting customer/payment',
            self::Quoted->value => 'Quote/confirmation sent',
            self::Confirmed->value => 'Confirmed',
            self::Completed->value => 'Completed',
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
            self::Confirmed => [self::Completed, self::Cancelled],
            self::Completed => [],
            self::Declined => [],
            self::Cancelled => [],
        }, true);
    }

    public function isResolved(): bool
    {
        return in_array($this, [self::Confirmed, self::Completed, self::Declined, self::Cancelled], true);
    }
}
