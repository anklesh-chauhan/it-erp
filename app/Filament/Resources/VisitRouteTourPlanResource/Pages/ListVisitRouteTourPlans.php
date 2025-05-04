<?php

namespace App\Filament\Resources\VisitRouteTourPlanResource\Pages;

use App\Filament\Resources\VisitRouteTourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisitRouteTourPlans extends ListRecords
{
    protected static string $resource = VisitRouteTourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
