<?php

namespace App\Filament\Resources\TransportModeResource\Pages;

use App\Filament\Resources\TransportModeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransportMode extends EditRecord
{
    protected static string $resource = TransportModeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
