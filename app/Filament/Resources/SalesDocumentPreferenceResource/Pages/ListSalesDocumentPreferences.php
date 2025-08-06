<?php

namespace App\Filament\Resources\SalesDocumentPreferenceResource\Pages;

use App\Filament\Resources\SalesDocumentPreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesDocumentPreferences extends ListRecords
{
    protected static string $resource = SalesDocumentPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
