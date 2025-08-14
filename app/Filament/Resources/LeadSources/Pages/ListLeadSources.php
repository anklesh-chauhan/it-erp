<?php

namespace App\Filament\Resources\LeadSources\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\LeadSources\LeadSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadSources extends ListRecords
{
    protected static string $resource = LeadSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
