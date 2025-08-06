<?php

namespace App\Filament\Resources\SalesInvoiceResource\Pages;

use App\Filament\Resources\SalesInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\SalesDocumentResourceTrait;

class CreateSalesInvoice extends CreateRecord
{
    use SalesDocumentResourceTrait;
    protected static string $resource = SalesInvoiceResource::class;
}
