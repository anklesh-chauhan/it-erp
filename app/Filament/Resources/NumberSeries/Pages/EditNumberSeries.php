<?php

namespace App\Filament\Resources\NumberSeries\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\NumberSeries\NumberSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNumberSeries extends EditRecord
{
    protected static string $resource = NumberSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
