<?php

namespace App\Filament\Resources\VisitRouteTourPlans\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\VisitRouteTourPlans\VisitRouteTourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitRouteTourPlan extends EditRecord
{
    protected static string $resource = VisitRouteTourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
