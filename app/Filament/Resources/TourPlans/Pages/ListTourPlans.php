<?php

namespace App\Filament\Resources\TourPlanResource\Pages;

use App\Filament\Resources\TourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourPlans extends ListRecords
{
    protected static string $resource = TourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
