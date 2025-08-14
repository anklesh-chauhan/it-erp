<?php

namespace App\Filament\Resources\SalesDailyExpenses\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SalesDailyExpenses\SalesDailyExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesDailyExpense extends EditRecord
{
    protected static string $resource = SalesDailyExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
