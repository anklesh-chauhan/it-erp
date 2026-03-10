<?php

namespace App\Filament\Resources\StandardFareCharts\Pages;

use App\Filament\Resources\StandardFareCharts\StandardFareChartResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditStandardFareChart extends EditRecord
{
    protected static string $resource = StandardFareChartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
