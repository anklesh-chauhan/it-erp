<?php

namespace App\Filament\Resources\TypeMasters\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TypeMasters\TypeMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypeMasters extends ListRecords
{
    protected static string $resource = TypeMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
