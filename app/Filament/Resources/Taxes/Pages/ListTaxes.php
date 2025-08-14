<?php

namespace App\Filament\Resources\Taxes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Taxes\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaxes extends ListRecords
{
    protected static string $resource = TaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
