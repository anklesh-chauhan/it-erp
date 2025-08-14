<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use App\Filament\Resources\DealResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use App\Models\Lead;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Checkbox; // Import Checkbox component
use Filament\Forms\Components\Select;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Convert to Deal')
                ->color('success')
                ->requiresConfirmation()
                ->form([
                    Checkbox::make('create_account_master')
                        ->label('Create Account Master')
                        ->default(false)
                        ->visible(function ($get, $record) {
                            return $record->company?->account_master_id === null;
                        }),
                ])
                ->action(function (array $data) {
                    $lead = $this->record; // Get the current lead record

                    $createAccountMaster = $data['create_account_master'] ?? false;

                    $deal = $lead->convertToDeal(createAccountMaster: $createAccountMaster);

                    Notification::make()
                        ->title('Lead Converted Successfully!')
                        ->body("Lead {$lead->reference_code} has been converted to Deal {$deal->reference_code}.")
                        ->success()
                        ->send();

                    return redirect(DealResource::getUrl('edit', ['record' => $deal->id])); // Redirect to the new Deal
                })
                ->visible(fn ($record) => $record->status?->name !== 'Converted'),

            Action::make('previous')
                ->label('')
                ->icon('heroicon-o-arrow-left')
                ->url(fn () => $this->getPreviousRecordUrl())
                ->disabled(is_null($this->getPreviousRecordId())),

            Action::make('next')
                ->label('')
                ->icon('heroicon-o-arrow-right')
                ->url(fn () => $this->getNextRecordUrl())
                ->disabled(is_null($this->getNextRecordId())),

            Actions\DeleteAction::make(),
        ];
    }

    public function convertToDeal()
    {
        $lead = $this->record; // Get the current lead record
        $deal = $lead->convertToDeal(); // Call the method from Lead model

        Notification::make()
            ->title('Lead Converted Successfully!')
            ->success()
            ->send();

        return redirect(DealResource::getUrl('edit', ['record' => $deal->id])); // Redirect to the new Deal
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['custom_fields'])) {
            $data['custom_fields'] = collect($data['custom_fields'])->toArray();
        }

        return $data;
    }


    private function getPreviousRecordId(): ?int
    {
        if (!$this->record) {
            return null;
        }

        return Lead::where('id', '<', $this->record->id)
            ->orderBy('id', 'desc')
            ->value('id');
    }

    private function getNextRecordId(): ?int
    {
        if (!$this->record) {
            return null;
        }

        return Lead::where('id', '>', $this->record->id)
            ->orderBy('id')
            ->value('id');
    }


    private function getPreviousRecordUrl(): ?string
    {
        if ($previousId = $this->getPreviousRecordId()) {
            return LeadResource::getUrl('edit', ['record' => $previousId]);
        }

        return null;
    }

    private function getNextRecordUrl(): ?string
    {
        if ($nextId = $this->getNextRecordId()) {
            return LeadResource::getUrl('edit', ['record' => $nextId]);
        }

        return null;
    }

}
