<?php

namespace App\Filament\Resources\ExpenseConfigurations\Pages;

use App\Filament\Resources\ExpenseConfigurations\ExpenseConfigurationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditExpenseConfiguration extends EditRecord
{
    protected static string $resource = ExpenseConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
