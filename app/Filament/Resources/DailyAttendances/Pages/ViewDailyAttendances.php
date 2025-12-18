<?php

namespace App\Filament\Resources\DailyAttendances\Pages;

use App\Filament\Resources\DailyAttendances\DailyAttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDailyAttendances extends ViewRecord
{
    protected static string $resource = DailyAttendanceResource::class;
}
