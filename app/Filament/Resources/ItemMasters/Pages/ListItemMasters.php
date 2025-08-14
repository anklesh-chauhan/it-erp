<?php

namespace App\Filament\Resources\ItemMasters\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ItemMasters\ItemMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemMasters extends ListRecords
{
    protected static string $resource = ItemMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
