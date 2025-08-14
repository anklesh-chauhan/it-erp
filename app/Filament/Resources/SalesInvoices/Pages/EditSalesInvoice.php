<?php

namespace App\Filament\Resources\SalesInvoices\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SalesInvoices\SalesInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\SalesDocumentResourceTrait;

class EditSalesInvoice extends EditRecord
{
    use SalesDocumentResourceTrait;
    protected static string $resource = SalesInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
