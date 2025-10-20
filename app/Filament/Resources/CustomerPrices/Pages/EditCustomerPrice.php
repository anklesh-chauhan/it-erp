<?php

namespace App\Filament\Resources\CustomerPrices\Pages;

use App\Filament\Resources\CustomerPrices\CustomerPriceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerPrice extends EditRecord
{
    protected static string $resource = CustomerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
