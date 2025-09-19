<?php

namespace App\Filament\Resources\SalesOrders\Pages;

use App\Filament\Resources\SalesOrders\SalesOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\SalesDocumentResourceTrait;

class CreateSalesOrder extends CreateRecord
{
    use SalesDocumentResourceTrait;
    protected static string $resource = SalesOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            static::getSalesDocumentPreferenceAction(), // ⚙ at top
        ];
    }
}
