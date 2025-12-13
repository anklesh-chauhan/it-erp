<?php

namespace App\Filament\Resources\EmployeeAttendanceStatuses\Pages;

use App\Filament\Resources\EmployeeAttendanceStatuses\EmployeeAttendanceStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeAttendanceStatus extends CreateRecord
{
    protected static string $resource = EmployeeAttendanceStatusResource::class;
}
