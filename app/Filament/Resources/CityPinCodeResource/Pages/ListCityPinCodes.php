<?php

namespace App\Filament\Resources\CityPinCodeResource\Pages;

use App\Filament\Resources\CityPinCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCityPinCodes extends ListRecords
{
    protected static string $resource = CityPinCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
