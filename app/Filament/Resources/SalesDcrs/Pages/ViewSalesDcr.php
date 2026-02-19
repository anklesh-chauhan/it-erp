<?php

namespace App\Filament\Resources\SalesDcrs\Pages;

use App\Filament\Resources\SalesDcrs\SalesDcrResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesDcr extends ViewRecord
{
    protected static string $resource = SalesDcrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
