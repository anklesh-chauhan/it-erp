<?php

namespace App\Filament\Resources\CustomerPrices\Pages;

use App\Filament\Resources\CustomerPrices\CustomerPriceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerPrices extends ListRecords
{
    protected static string $resource = CustomerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
