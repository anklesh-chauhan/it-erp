<?php

namespace App\Filament\Resources\Patches\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Patches\PatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatch extends EditRecord
{
    protected static string $resource = PatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
