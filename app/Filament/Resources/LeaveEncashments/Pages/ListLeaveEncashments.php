<?php

namespace App\Filament\Resources\LeaveEncashments\Pages;

use App\Filament\Resources\LeaveEncashments\LeaveEncashmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveEncashments extends ListRecords
{
    protected static string $resource = LeaveEncashmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
