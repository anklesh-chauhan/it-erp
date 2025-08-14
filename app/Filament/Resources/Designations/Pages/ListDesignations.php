<?php

namespace App\Filament\Resources\Designations\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Designations\DesignationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDesignations extends ListRecords
{
    protected static string $resource = DesignationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
