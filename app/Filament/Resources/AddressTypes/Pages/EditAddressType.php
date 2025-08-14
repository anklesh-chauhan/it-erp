<?php

namespace App\Filament\Resources\AddressTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AddressTypes\AddressTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddressType extends EditRecord
{
    protected static string $resource = AddressTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
