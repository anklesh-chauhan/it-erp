<?php

namespace App\Filament\Resources\SalesDcrExpenses\Pages;

use App\Filament\Resources\SalesDcrExpenses\SalesDcrExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalesDcrExpenses extends ListRecords
{
    protected static string $resource = SalesDcrExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
