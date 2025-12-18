<?php

namespace App\Filament\Resources\ShiftMasters\Pages;

use App\Filament\Resources\ShiftMasters\ShiftMasterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShiftMaster extends EditRecord
{
    protected static string $resource = ShiftMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
