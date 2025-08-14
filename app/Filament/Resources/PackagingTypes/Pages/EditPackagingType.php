<?php

namespace App\Filament\Resources\PackagingTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\PackagingTypes\PackagingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackagingType extends EditRecord
{
    protected static string $resource = PackagingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
