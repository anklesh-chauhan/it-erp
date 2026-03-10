<?php

namespace App\Filament\Resources\StandardFareCharts\Pages;

use App\Filament\Resources\StandardFareCharts\StandardFareChartResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStandardFareCharts extends ListRecords
{
    protected static string $resource = StandardFareChartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
