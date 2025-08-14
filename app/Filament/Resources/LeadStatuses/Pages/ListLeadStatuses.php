<?php

namespace App\Filament\Resources\LeadStatuses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\LeadStatuses\LeadStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadStatuses extends ListRecords
{
    protected static string $resource = LeadStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
