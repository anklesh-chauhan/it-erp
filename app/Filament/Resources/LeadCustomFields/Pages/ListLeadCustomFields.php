<?php

namespace App\Filament\Resources\LeadCustomFields\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\LeadCustomFields\LeadCustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadCustomFields extends ListRecords
{
    protected static string $resource = LeadCustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
