<?php

namespace App\Filament\Resources\ExpenseConfigurationResource\Pages;

use App\Filament\Resources\ExpenseConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenseConfigurations extends ListRecords
{
    protected static string $resource = ExpenseConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
