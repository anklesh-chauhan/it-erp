<?php

namespace App\Filament\Resources\PackagingTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\PackagingTypes\PackagingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackagingTypes extends ListRecords
{
    protected static string $resource = PackagingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
