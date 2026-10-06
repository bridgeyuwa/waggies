<?php

namespace App\Filament\Resources\BookingRequests\Pages;

use App\Enums\BookingRequestStatus;
use App\Filament\Resources\BookingRequests\BookingRequestResource;
use App\Models\BookingRequest;
use App\Support\BookingRequestCorrectionLogger;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditBookingRequest extends EditRecord
{
    protected static string $resource = BookingRequestResource::class;

    protected function afterSave(): void
    {
        /** @var BookingRequest $record */
        $record = $this->getRecord();
        $record->syncLegacyServiceMessage();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof BookingRequest) {
            return parent::handleRecordUpdate($record, $data);
        }

        return DB::transaction(function () use ($record, $data): Model {
            $trackedAttributes = array_intersect_key($data, array_flip([
                'name',
                'email',
                'phone',
                'preferred_contact_method',
                'message',
                'internal_notes',
            ]));
            $oldValues = array_intersect_key($record->getAttributes(), $trackedAttributes);

            $record->update($data);

            $newValues = array_intersect_key($record->getAttributes(), $trackedAttributes);
            app(BookingRequestCorrectionLogger::class)->record(
                $record,
                'Booking request details corrected',
                $oldValues,
                $newValues,
            );

            return $record;
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                $this->statusAction('startReview', 'Start review', BookingRequestStatus::Reviewing),
                $this->statusAction('markQuoted', 'Mark quoted', BookingRequestStatus::Quoted),
                $this->statusAction('confirmRequest', 'Confirm request', BookingRequestStatus::Confirmed),
                $this->statusAction('declineRequest', 'Decline request', BookingRequestStatus::Declined),
                $this->statusAction('cancelRequest', 'Cancel request', BookingRequestStatus::Cancelled),
                $this->statusAction('completeRequest', 'Mark completed', BookingRequestStatus::Completed),
            ])
                ->label('Progress request')
                ->button()
                ->color('primary'),
            DeleteAction::make()
                ->label('Delete booking'),
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
