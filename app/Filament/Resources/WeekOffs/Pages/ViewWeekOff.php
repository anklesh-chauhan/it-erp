<?php

namespace App\Filament\Resources\WeekOffs\Pages;

use App\Filament\Resources\WeekOffs\WeekOffResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWeekOff extends ViewRecord
{
    protected static string $resource = WeekOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
