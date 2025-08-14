<?php

namespace App\Filament\Resources\Territories\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Territories\TerritoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerritories extends ListRecords
{
    protected static string $resource = TerritoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
