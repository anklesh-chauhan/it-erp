<?php

namespace App\Filament\Resources\UnitOfMeasurements\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\UnitOfMeasurements\UnitOfMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnitOfMeasurement extends EditRecord
{
    protected static string $resource = UnitOfMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
