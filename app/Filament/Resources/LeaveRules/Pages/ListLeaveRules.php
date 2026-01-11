<?php

namespace App\Filament\Resources\LeaveRules\Pages;

use App\Filament\Resources\LeaveRules\LeaveRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRules extends ListRecords
{
    protected static string $resource = LeaveRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
