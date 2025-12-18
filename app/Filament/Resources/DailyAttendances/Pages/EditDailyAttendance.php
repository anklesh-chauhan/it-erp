<?php

namespace App\Filament\Resources\DailyAttendances\Pages;

use App\Filament\Resources\DailyAttendances\DailyAttendanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyAttendance extends EditRecord
{
    protected static string $resource = DailyAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
