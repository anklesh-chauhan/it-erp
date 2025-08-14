<?php

namespace App\Filament\Resources\TourPlans\Pages;

use App\Filament\Resources\TourPlans\TourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTourPlan extends CreateRecord
{
    protected static string $resource = TourPlanResource::class;
}
