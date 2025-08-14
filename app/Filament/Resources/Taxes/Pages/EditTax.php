<?php

namespace App\Filament\Resources\Taxes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Taxes\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTax extends EditRecord
{
    protected static string $resource = TaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
