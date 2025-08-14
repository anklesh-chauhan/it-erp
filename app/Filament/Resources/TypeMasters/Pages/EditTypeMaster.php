<?php

namespace App\Filament\Resources\TypeMasterResource\Pages;

use App\Filament\Resources\TypeMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeMaster extends EditRecord
{
    protected static string $resource = TypeMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
