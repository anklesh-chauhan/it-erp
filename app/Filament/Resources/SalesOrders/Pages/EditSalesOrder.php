<?php

namespace App\Filament\Resources\SalesOrders\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SalesOrders\SalesOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\SalesDocumentResourceTrait;

class EditSalesOrder extends EditRecord
{
    use SalesDocumentResourceTrait;
    protected static string $resource = SalesOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
