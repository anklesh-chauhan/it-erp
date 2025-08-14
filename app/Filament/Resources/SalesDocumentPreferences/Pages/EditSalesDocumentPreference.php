<?php

namespace App\Filament\Resources\SalesDocumentPreferenceResource\Pages;

use App\Filament\Resources\SalesDocumentPreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesDocumentPreference extends EditRecord
{
    protected static string $resource = SalesDocumentPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
