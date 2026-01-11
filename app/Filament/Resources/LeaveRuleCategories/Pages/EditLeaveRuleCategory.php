<?php

namespace App\Filament\Resources\LeaveRuleCategories\Pages;

use App\Filament\Resources\LeaveRuleCategories\LeaveRuleCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRuleCategory extends EditRecord
{
    protected static string $resource = LeaveRuleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
