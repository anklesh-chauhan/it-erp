<?php

namespace App\Filament\Resources\UnitOfMeasurements\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\UnitOfMeasurements\UnitOfMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitOfMeasurements extends ListRecords
{
    protected static string $resource = UnitOfMeasurementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
