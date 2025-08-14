<?php

namespace App\Filament\Resources\FollowUpResource\Pages;

use App\Filament\Resources\FollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use App\Models\FollowUp;

class EditFollowUp extends EditRecord
{
    protected static string $resource = FollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
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

    private function getPreviousRecordId(): ?int
    {
        if (!$this->record) {
            return null;
        }

        return FollowUp::where('id', '<', $this->record->id)
            ->orderBy('id', 'desc')
            ->value('id');
    }

    private function getNextRecordId(): ?int
    {
        if (!$this->record) {
            return null;
        }

        return FollowUp::where('id', '>', $this->record->id)
            ->orderBy('id')
            ->value('id');
    }


    private function getPreviousRecordUrl(): ?string
    {
        if ($previousId = $this->getPreviousRecordId()) {
            return FollowUpResource::getUrl('edit', ['record' => $previousId]);
        }

        return null;
    }

    private function getNextRecordUrl(): ?string
    {
        if ($nextId = $this->getNextRecordId()) {
            return FollowUpResource::getUrl('edit', ['record' => $nextId]);
        }

        return null;
    }
}
