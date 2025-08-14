<?php

namespace App\Filament\Resources\LeadActivities\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\LeadActivities\LeadActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadActivities extends ListRecords
{
    protected static string $resource = LeadActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
