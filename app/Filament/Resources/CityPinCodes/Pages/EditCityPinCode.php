<?php

namespace App\Filament\Resources\CityPinCodeResource\Pages;

use App\Filament\Resources\CityPinCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCityPinCode extends EditRecord
{
    protected static string $resource = CityPinCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
