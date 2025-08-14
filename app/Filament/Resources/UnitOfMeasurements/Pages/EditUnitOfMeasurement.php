<?php

namespace App\Filament\Resources\UnitOfMeasurementResource\Pages;

use App\Filament\Resources\UnitOfMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnitOfMeasurement extends EditRecord
{
    protected static string $resource = UnitOfMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
