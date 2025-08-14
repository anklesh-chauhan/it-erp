<?php

namespace App\Filament\Resources\TourPlans\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TourPlans\TourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourPlan extends EditRecord
{
    protected static string $resource = TourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
