<?php

namespace App\Filament\Resources\SalesDailyExpenses\Pages;

use App\Filament\Resources\SalesDailyExpenses\SalesDailyExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesDailyExpense extends CreateRecord
{
    protected static string $resource = SalesDailyExpenseResource::class;
}
