<?php

namespace App\Filament\Resources\SalesDailyExpenses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SalesDailyExpenses\SalesDailyExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesDailyExpenses extends ListRecords
{
    protected static string $resource = SalesDailyExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
