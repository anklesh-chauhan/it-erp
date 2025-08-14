<?php

namespace App\Filament\Resources\CityPinCodes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CityPinCodes\CityPinCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCityPinCodes extends ListRecords
{
    protected static string $resource = CityPinCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
