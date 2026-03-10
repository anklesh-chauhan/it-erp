<?php

namespace App;

use App\Models\SalesDcr;
use App\Services\Expense\ExpenseCalculationService;
use Filament\Actions\Action;

class AutoCalculateExpensesAction
{
    public static function make(): Action
    {
        return Action::make('autoCalculateExpenses')
            ->label('Auto Calculate Expenses')
            ->requiresConfirmation()
            ->action(function (SalesDcr $record, ExpenseCalculationService $service): void {
                $service->autoCalculateDcrExpenses($record);
            });
    }
}
