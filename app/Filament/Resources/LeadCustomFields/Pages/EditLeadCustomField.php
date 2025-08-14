<?php

namespace App\Filament\Resources\LeadCustomFields\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\LeadCustomFields\LeadCustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadCustomField extends EditRecord
{
    protected static string $resource = LeadCustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
