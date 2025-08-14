<?php

namespace App\Filament\Resources\LeadCustomFieldResource\Pages;

use App\Filament\Resources\LeadCustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadCustomField extends EditRecord
{
    protected static string $resource = LeadCustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
