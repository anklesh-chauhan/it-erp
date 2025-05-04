<?php

namespace App\Filament\Resources\ItemMeasurementUnitResource\Pages;

use App\Filament\Resources\ItemMeasurementUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemMeasurementUnits extends ListRecords
{
    protected static string $resource = ItemMeasurementUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
