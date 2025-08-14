<?php

namespace App\Filament\Resources\SalesDcrs\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SalesDcrs\SalesDcrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesDcrs extends ListRecords
{
    protected static string $resource = SalesDcrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
