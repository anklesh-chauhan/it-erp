<?php

namespace App\Filament\Resources\VisitRouteTourPlanResource\Pages;

use App\Filament\Resources\VisitRouteTourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitRouteTourPlan extends EditRecord
{
    protected static string $resource = VisitRouteTourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
