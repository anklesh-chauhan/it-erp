<?php

namespace App\Filament\Resources\SampleRequests\Pages;

use App\Enums\SampleRequestStatus;
use App\Filament\Resources\SampleRequests\SampleRequestResource;
use App\Models\SampleRequestLine;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSampleRequest extends EditRecord
{
    protected static string $resource = SampleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submit')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === SampleRequestStatus::Draft)
                ->action(function (): void {
                    if (! $this->record->lines()->exists()) {
                        Notification::make()->title('Add at least one requested item.')->danger()->send();

                        return;
                    }

                    $this->record->update(['status' => SampleRequestStatus::Submitted]);
                    $this->refreshFormData(['status']);
                }),

            Action::make('approve')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === SampleRequestStatus::Submitted)
                ->action(function (): void {
                    $invalidLineExists = $this->record->lines()
                        ->get()
                        ->contains(fn (SampleRequestLine $line): bool => (float) $line->quantity_approved <= 0
                            || (float) $line->quantity_approved > (float) $line->quantity_requested);

                    if ($invalidLineExists) {
                        Notification::make()
                            ->title('Enter a valid approved quantity for every line.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $this->record->update(['status' => SampleRequestStatus::Approved]);
                    $this->refreshFormData(['status']);
                }),

            Action::make('cancel')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => in_array($this->record->status, [
                    SampleRequestStatus::Draft,
                    SampleRequestStatus::Submitted,
                    SampleRequestStatus::Approved,
                ], true))
                ->action(function (): void {
                    $this->record->update(['status' => SampleRequestStatus::Cancelled]);
                    $this->refreshFormData(['status']);
                }),

            DeleteAction::make()
                ->visible(fn (): bool => $this->record->status === SampleRequestStatus::Draft),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! $this->record->isEditable()) {
            Notification::make()->title('This sample request is locked.')->danger()->send();
            $this->halt();
        }

        return $data;
    }

    protected function canEdit(): bool
    {
        return $this->record->isEditable();
    }
}
