<?php

namespace App\Filament\Resources\SalesDcrExpenses\Pages;

use App\Filament\Resources\SalesDcrExpenses\SalesDcrExpenseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSalesDcrExpense extends EditRecord
{
    protected static string $resource = SalesDcrExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
