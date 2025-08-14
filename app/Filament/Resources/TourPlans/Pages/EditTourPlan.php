<?php

namespace App\Filament\Resources\TourPlanResource\Pages;

use App\Filament\Resources\TourPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourPlan extends EditRecord
{
    protected static string $resource = TourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
