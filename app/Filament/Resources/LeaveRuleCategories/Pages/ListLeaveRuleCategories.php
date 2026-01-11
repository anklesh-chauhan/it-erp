<?php

namespace App\Filament\Resources\LeaveRuleCategories\Pages;

use App\Filament\Resources\LeaveRuleCategories\LeaveRuleCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRuleCategories extends ListRecords
{
    protected static string $resource = LeaveRuleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
