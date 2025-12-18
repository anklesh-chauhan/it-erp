<?php

namespace App\Filament\Resources\ShiftMasters\Pages;

use App\Filament\Resources\ShiftMasters\ShiftMasterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShiftMasters extends ListRecords
{
    protected static string $resource = ShiftMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
