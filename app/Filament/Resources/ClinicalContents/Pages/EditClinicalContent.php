<?php

namespace App\Filament\Resources\ClinicalContents\Pages;

use App\Actions\ClinicalContentWorkflow;
use App\Enums\ClinicalContentStatus;
use App\Filament\Resources\ClinicalContents\ClinicalContentResource;
use App\Models\ClinicalContent;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use LogicException;

class EditClinicalContent extends EditRecord
{
    protected static string $resource = ClinicalContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve for publication')
                ->color('success')
                ->authorize('approve')
                ->visible(fn (ClinicalContent $record): bool => $record->enumValue('clinical_status') !== ClinicalContentStatus::Approved->value)
                ->requiresConfirmation()
                ->action(function (ClinicalContent $record, ClinicalContentWorkflow $workflow): void {
                    $reviewer = auth()->user();

                    if (! $reviewer instanceof User) {
                        return;
                    }

                    try {
                        $workflow->approve($record, $reviewer);
                    } catch (LogicException $exception) {
                        Notification::make()
                            ->title('Clinical content was not approved')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Clinical content approved')
                        ->success()
                        ->send();
                }),
            Action::make('requestChanges')
                ->label('Request changes')
                ->color('warning')
                ->authorize('requestChanges')
                ->schema([
                    Textarea::make('review_notes')
                        ->label('What needs to change?')
                        ->required()
                        ->maxLength(5000),
                ])
                ->action(function (array $data, ClinicalContent $record, ClinicalContentWorkflow $workflow): void {
                    $reviewer = auth()->user();

                    if (! $reviewer instanceof User) {
                        return;
                    }

                    $workflow->requestChanges($record, $reviewer, $data['review_notes']);

                    Notification::make()
                        ->title('Changes requested')
                        ->success()
                        ->send();
                }),
            Action::make('publish')
                ->label('Publish')
                ->color('primary')
                ->authorize('publish')
                ->visible(fn (ClinicalContent $record): bool => $record->enumValue('clinical_status') === ClinicalContentStatus::Approved->value && $record->enumValue('publication_status') !== 'published')
                ->requiresConfirmation()
                ->action(function (ClinicalContent $record, ClinicalContentWorkflow $workflow): void {
                    try {
                        $workflow->publish($record);
                    } catch (LogicException $exception) {
                        Notification::make()
                            ->title('Clinical content cannot be published')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Clinical content published')
                        ->success()
                        ->send();
                }),
            Action::make('withdraw')
                ->label('Withdraw')
                ->color('danger')
                ->authorize('withdraw')
                ->schema([
                    Textarea::make('withdrawal_reason')
                        ->label('Why is this being withdrawn?')
                        ->required()
                        ->maxLength(5000),
                ])
                ->action(function (array $data, ClinicalContent $record, ClinicalContentWorkflow $workflow): void {
                    $reviewer = auth()->user();

                    if (! $reviewer instanceof User) {
                        return;
                    }

                    $workflow->withdraw($record, $data['withdrawal_reason'], $reviewer);

                    Notification::make()
                        ->title('Clinical content withdrawn')
                        ->success()
                        ->send();
                }),
        ];
    }
}
