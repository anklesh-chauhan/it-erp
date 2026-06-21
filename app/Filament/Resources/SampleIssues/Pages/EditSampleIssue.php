<?php

namespace App\Filament\Resources\SampleIssues\Pages;

use App\Filament\Resources\SampleIssues\SampleIssueResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Throwable;

class EditSampleIssue extends EditRecord
{
    protected static string $resource = SampleIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('post')
                ->label('Post to Stock')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('This transfers approved samples from the warehouse to the representative location.')
                ->visible(fn (): bool => $this->record->isEditable())
                ->action(function (): void {
                    try {
                        $this->record->post();
                        Notification::make()->title('Sample issue posted.')->success()->send();
                        $this->refreshFormData(['status', 'posted_at', 'issued_by']);
                    } catch (Throwable $exception) {
                        Notification::make()
                            ->title('Unable to post sample issue')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            DeleteAction::make()
                ->visible(fn (): bool => $this->record->isEditable()),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! $this->record->isEditable()) {
            Notification::make()->title('This sample issue is locked.')->danger()->send();
            $this->halt();
        }

        return $data;
    }

    protected function canEdit(): bool
    {
        return $this->record->isEditable();
    }
}
