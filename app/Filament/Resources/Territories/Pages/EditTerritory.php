<?php

namespace App\Filament\Resources\Territories\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Territories\TerritoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTerritory extends EditRecord
{
    protected static string $resource = TerritoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
