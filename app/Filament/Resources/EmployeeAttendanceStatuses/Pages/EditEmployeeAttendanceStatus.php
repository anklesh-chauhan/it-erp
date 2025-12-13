<?php

namespace App\Filament\Resources\EmployeeAttendanceStatuses\Pages;

use App\Filament\Resources\EmployeeAttendanceStatuses\EmployeeAttendanceStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeAttendanceStatus extends EditRecord
{
    protected static string $resource = EmployeeAttendanceStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
