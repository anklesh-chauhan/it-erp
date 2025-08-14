<?php

namespace App\Filament\Resources\ItemBrands\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ItemBrands\ItemBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemBrands extends ListRecords
{
    protected static string $resource = ItemBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
