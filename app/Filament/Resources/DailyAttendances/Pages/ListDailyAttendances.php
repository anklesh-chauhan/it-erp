<?php

namespace App\Filament\Resources\DailyAttendances\Pages;

use App\Filament\Resources\DailyAttendances\DailyAttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyAttendances extends ListRecords
{
    protected static string $resource = DailyAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
