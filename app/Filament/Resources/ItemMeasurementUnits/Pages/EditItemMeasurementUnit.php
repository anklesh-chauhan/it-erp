<?php

namespace App\Filament\Resources\ItemMeasurementUnits\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ItemMeasurementUnits\ItemMeasurementUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemMeasurementUnit extends EditRecord
{
    protected static string $resource = ItemMeasurementUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
