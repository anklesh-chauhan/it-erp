<?php

namespace App\Filament\Resources\VisitRouteTourPlans\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\VisitRouteTourPlans\VisitRouteTourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisitRouteTourPlans extends ListRecords
{
    protected static string $resource = VisitRouteTourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
