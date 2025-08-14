<?php

namespace App\Filament\Resources\SalesOrderResource\Pages;

use App\Filament\Resources\SalesOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\SalesDocumentResourceTrait;

class CreateSalesOrder extends CreateRecord
{
    use SalesDocumentResourceTrait;
    protected static string $resource = SalesOrderResource::class;
}
