<?php

namespace App\Filament\Resources\LeadCustomFieldResource\Pages;

use App\Filament\Resources\LeadCustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadCustomFields extends ListRecords
{
    protected static string $resource = LeadCustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
