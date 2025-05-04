<?php

namespace App\Filament\Resources\LocationMasterResource\Pages;

use App\Filament\Resources\LocationMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocationMasters extends ListRecords
{
    protected static string $resource = LocationMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
