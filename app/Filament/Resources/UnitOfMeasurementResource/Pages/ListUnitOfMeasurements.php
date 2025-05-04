<?php

namespace App\Filament\Resources\UnitOfMeasurementResource\Pages;

use App\Filament\Resources\UnitOfMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitOfMeasurements extends ListRecords
{
    protected static string $resource = UnitOfMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
