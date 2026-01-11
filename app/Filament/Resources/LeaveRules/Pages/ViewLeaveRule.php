<?php

namespace App\Filament\Resources\LeaveRules\Pages;

use App\Filament\Resources\LeaveRules\LeaveRuleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeaveRule extends ViewRecord
{
    protected static string $resource = LeaveRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
