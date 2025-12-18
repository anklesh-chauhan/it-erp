<?php

namespace App\Filament\Resources\DailyAttendances\Pages;

use App\Filament\Resources\DailyAttendances\DailyAttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyAttendance extends CreateRecord
{
    protected static string $resource = DailyAttendanceResource::class;
}
