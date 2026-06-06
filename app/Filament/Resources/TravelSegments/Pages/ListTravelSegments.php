<?php

namespace App\Filament\Resources\TravelSegments\Pages;

use App\Filament\Resources\TravelSegments\TravelSegmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTravelSegments extends ListRecords
{
    protected static string $resource = TravelSegmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
