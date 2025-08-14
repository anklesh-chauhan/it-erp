<?php

namespace App\Filament\Resources\TourPlans\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TourPlans\TourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourPlans extends ListRecords
{
    protected static string $resource = TourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
