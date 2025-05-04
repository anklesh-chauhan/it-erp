<?php

namespace App\Filament\Resources\NumberSeriesResource\Pages;

use App\Filament\Resources\NumberSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNumberSeries extends ListRecords
{
    protected static string $resource = NumberSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
