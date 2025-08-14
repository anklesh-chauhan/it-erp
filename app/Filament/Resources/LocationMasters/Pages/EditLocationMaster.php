<?php

namespace App\Filament\Resources\LocationMasterResource\Pages;

use App\Filament\Resources\LocationMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocationMaster extends EditRecord
{
    protected static string $resource = LocationMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
