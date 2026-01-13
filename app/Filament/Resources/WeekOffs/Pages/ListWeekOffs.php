<?php

namespace App\Filament\Resources\WeekOffs\Pages;

use App\Filament\Resources\WeekOffs\WeekOffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeekOffs extends ListRecords
{
    protected static string $resource = WeekOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
