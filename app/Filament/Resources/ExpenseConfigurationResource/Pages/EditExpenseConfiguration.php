<?php

namespace App\Filament\Resources\ExpenseConfigurationResource\Pages;

use App\Filament\Resources\ExpenseConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpenseConfiguration extends EditRecord
{
    protected static string $resource = ExpenseConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
