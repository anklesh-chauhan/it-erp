<?php

namespace App\Filament\Resources\SgipDistributions\Pages;

use App\Filament\Resources\SgipDistributions\SgipDistributionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSgipDistribution extends EditRecord
{
    protected static string $resource = SgipDistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function canEdit(): bool
    {
        return $this->record->status === 'draft';
    }
}
