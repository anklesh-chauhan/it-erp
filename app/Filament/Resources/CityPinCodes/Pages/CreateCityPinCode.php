<?php

namespace App\Filament\Resources\CityPinCodes\Pages;

use App\Filament\Resources\CityPinCodes\CityPinCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCityPinCode extends CreateRecord
{
    protected static string $resource = CityPinCodeResource::class;
}
