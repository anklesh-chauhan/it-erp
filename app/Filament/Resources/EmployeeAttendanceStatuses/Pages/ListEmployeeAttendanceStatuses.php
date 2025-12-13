<?php

namespace App\Filament\Resources\EmployeeAttendanceStatuses\Pages;

use App\Filament\Resources\EmployeeAttendanceStatuses\EmployeeAttendanceStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAttendanceStatuses extends ListRecords
{
    protected static string $resource = EmployeeAttendanceStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
