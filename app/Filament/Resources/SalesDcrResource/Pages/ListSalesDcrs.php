<?php

namespace App\Filament\Resources\SalesDcrResource\Pages;

use App\Filament\Resources\SalesDcrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesDcrs extends ListRecords
{
    protected static string $resource = SalesDcrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
