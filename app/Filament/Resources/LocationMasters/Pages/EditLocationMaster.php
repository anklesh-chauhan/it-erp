<?php

namespace App\Filament\Resources\LocationMasters\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\LocationMasters\LocationMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocationMaster extends EditRecord
{
    protected static string $resource = LocationMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
