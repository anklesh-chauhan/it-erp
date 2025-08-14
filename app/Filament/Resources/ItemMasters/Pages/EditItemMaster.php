<?php

namespace App\Filament\Resources\ItemMasters\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ItemMasters\ItemMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemMaster extends EditRecord
{
    protected static string $resource = ItemMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
