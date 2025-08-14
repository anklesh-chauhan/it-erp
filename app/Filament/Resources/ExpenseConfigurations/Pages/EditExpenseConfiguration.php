<?php

namespace App\Filament\Resources\ExpenseConfigurations\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ExpenseConfigurations\ExpenseConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpenseConfiguration extends EditRecord
{
    protected static string $resource = ExpenseConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
