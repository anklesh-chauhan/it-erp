<?php

namespace App\Filament\Resources\TypeMasters\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TypeMasters\TypeMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeMaster extends EditRecord
{
    protected static string $resource = TypeMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
