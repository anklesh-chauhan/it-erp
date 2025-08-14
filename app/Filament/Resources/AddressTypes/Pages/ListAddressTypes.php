<?php

namespace App\Filament\Resources\AddressTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\AddressTypes\AddressTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddressTypes extends ListRecords
{
    protected static string $resource = AddressTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
