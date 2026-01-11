<?php

namespace App\Filament\Resources\LeaveInstances\Pages;

use App\Filament\Resources\LeaveInstances\LeaveInstanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveInstances extends ListRecords
{
    protected static string $resource = LeaveInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
