<?php

namespace App\Filament\Resources\NumberSeries\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\NumberSeries\NumberSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNumberSeries extends ListRecords
{
    protected static string $resource = NumberSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
