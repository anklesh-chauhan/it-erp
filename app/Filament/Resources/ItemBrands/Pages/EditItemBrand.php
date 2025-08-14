<?php

namespace App\Filament\Resources\ItemBrandResource\Pages;

use App\Filament\Resources\ItemBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemBrand extends EditRecord
{
    protected static string $resource = ItemBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
