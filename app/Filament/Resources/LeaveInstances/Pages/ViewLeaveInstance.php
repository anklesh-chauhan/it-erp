<?php

namespace App\Filament\Resources\LeaveInstances\Pages;

use App\Filament\Resources\LeaveInstances\LeaveInstanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeaveInstance extends ViewRecord
{
    protected static string $resource = LeaveInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
