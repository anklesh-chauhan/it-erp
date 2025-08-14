<?php

namespace App\Filament\Resources\CityPinCodes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CityPinCodes\CityPinCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCityPinCode extends EditRecord
{
    protected static string $resource = CityPinCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
