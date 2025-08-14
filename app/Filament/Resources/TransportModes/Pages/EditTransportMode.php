<?php

namespace App\Filament\Resources\TransportModes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TransportModes\TransportModeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransportMode extends EditRecord
{
    protected static string $resource = TransportModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
