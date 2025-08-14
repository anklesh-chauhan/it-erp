<?php

namespace App\Filament\Resources\TransportModes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TransportModes\TransportModeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransportModes extends ListRecords
{
    protected static string $resource = TransportModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
