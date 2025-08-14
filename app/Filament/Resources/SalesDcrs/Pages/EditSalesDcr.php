<?php

namespace App\Filament\Resources\SalesDcrs\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SalesDcrs\SalesDcrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesDcr extends EditRecord
{
    protected static string $resource = SalesDcrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
