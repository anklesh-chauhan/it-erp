<?php

namespace App\Filament\Resources\SalesDocumentPreferences\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SalesDocumentPreferences\SalesDocumentPreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesDocumentPreferences extends ListRecords
{
    protected static string $resource = SalesDocumentPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
