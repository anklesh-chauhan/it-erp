<?php

namespace App\Filament\Resources\SalesDailyExpenseResource\Pages;

use App\Filament\Resources\SalesDailyExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesDailyExpenses extends ListRecords
{
    protected static string $resource = SalesDailyExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
