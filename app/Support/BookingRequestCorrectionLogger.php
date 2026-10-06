<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Enums\ActivityEvent;

class BookingRequestCorrectionLogger
{
    /**
     * @param  array<string, mixed>  $oldValues
     * @param  array<string, mixed>  $newValues
     */
    public function record(Model $subject, string $description, array $oldValues, array $newValues): void
    {
        $changedAttributes = [];
        $previousValues = [];

        foreach ($newValues as $key => $newValue) {
            if (($oldValues[$key] ?? null) === $newValue) {
                continue;
            }

            $changedAttributes[$key] = $newValue;
            $previousValues[$key] = $oldValues[$key] ?? null;
        }

        if ($changedAttributes === []) {
            return;
        }

        activity('booking-requests')
            ->performedOn($subject)
            ->event(ActivityEvent::Updated)
            ->withProperties([
                'attributes' => $changedAttributes,
                'old' => $previousValues,
            ])
            ->log($description);
    }
}
