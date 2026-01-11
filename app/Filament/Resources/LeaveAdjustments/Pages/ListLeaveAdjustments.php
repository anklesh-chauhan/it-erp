<?php

namespace App\Filament\Resources\LeaveAdjustments\Pages;

use App\Filament\Resources\LeaveAdjustments\LeaveAdjustmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveAdjustments extends ListRecords
{
    protected static string $resource = LeaveAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
