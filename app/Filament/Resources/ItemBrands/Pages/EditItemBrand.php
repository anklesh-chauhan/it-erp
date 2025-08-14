<?php

namespace App\Filament\Resources\ItemBrands\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ItemBrands\ItemBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemBrand extends EditRecord
{
    protected static string $resource = ItemBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
