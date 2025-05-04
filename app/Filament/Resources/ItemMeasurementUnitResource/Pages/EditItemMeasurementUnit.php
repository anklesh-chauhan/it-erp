<?php

namespace App\Filament\Resources\ItemMeasurementUnitResource\Pages;

use App\Filament\Resources\ItemMeasurementUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemMeasurementUnit extends EditRecord
{
    protected static string $resource = ItemMeasurementUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
