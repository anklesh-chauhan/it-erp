<?php

namespace App\Filament\Resources\CustomerPrices\Pages;

use App\Filament\Resources\CustomerPrices\CustomerPriceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerPrice extends CreateRecord
{
    protected static string $resource = CustomerPriceResource::class;
}
