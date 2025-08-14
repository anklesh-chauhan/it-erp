<?php

namespace App\Filament\Resources\SalesDocumentPreferences\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SalesDocumentPreferences\SalesDocumentPreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesDocumentPreference extends EditRecord
{
    protected static string $resource = SalesDocumentPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
