<?php

namespace App\Filament\Resources\AddressTypeResource\Pages;

use App\Filament\Resources\AddressTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddressType extends EditRecord
{
    protected static string $resource = AddressTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
