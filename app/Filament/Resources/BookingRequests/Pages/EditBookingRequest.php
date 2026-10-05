<?php

namespace App\Filament\Resources\BookingRequests\Pages;

use App\Enums\BookingRequestStatus;
use App\Filament\Resources\BookingRequests\BookingRequestResource;
use App\Models\BookingRequest;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBookingRequest extends EditRecord
{
    protected static string $resource = BookingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->statusAction('startReview', 'Start review', BookingRequestStatus::Reviewing),
            $this->statusAction('markQuoted', 'Mark quoted', BookingRequestStatus::Quoted),
            $this->statusAction('confirmRequest', 'Confirm request', BookingRequestStatus::Confirmed),
            $this->statusAction('declineRequest', 'Decline request', BookingRequestStatus::Declined),
            $this->statusAction('cancelRequest', 'Cancel request', BookingRequestStatus::Cancelled),
            $this->statusAction('completeRequest', 'Mark completed', BookingRequestStatus::Completed),
        ];
    }

    private function statusAction(
        string $name,
        string $label,
        BookingRequestStatus $status,
    ): Action {
        return Action::make($name)
            ->label($label)
            ->action(function () use ($status, $label): void {
                /** @var BookingRequest $record */
                $record = $this->getRecord();
                $record->transitionTo($status);

                Notification::make()
                    ->title("Booking request {$label}")
                    ->success()
                    ->send();
            })
            ->disabled(function () use ($status): bool {
                /** @var BookingRequest $record */
                $record = $this->getRecord();

                return ! $record->canTransitionTo($status);
            });
    }
}
