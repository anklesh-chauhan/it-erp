<?php

namespace App\Filament\Resources\TransportModeResource\Pages;

use App\Filament\Resources\TransportModeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransportModes extends ListRecords
{
    protected static string $resource = TransportModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
