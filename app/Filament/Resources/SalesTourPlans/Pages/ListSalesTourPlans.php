<?php

namespace App\Filament\Resources\SalesTourPlans\Pages;

use App\Filament\Resources\SalesTourPlans\SalesTourPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalesTourPlans extends ListRecords
{
    protected static string $resource = SalesTourPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
