<?php

namespace App\Filament\Resources\ExpenseConfigurations\Pages;

use App\Filament\Resources\ExpenseConfigurations\ExpenseConfigurationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpenseConfigurations extends ListRecords
{
    protected static string $resource = ExpenseConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
