<?php

namespace App\Enums;

enum BookingRequestStatus: string
{
    case New = 'new';

    case Contacted = 'contacted';

    case Confirmed = 'confirmed';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::New->value => 'New',
            self::Contacted->value => 'Contacted',
            self::Confirmed->value => 'Confirmed',
            self::Completed->value => 'Completed',
            self::Cancelled->value => 'Cancelled',
        ];
    }
}
