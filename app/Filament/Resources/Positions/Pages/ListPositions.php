<?php

namespace App\Filament\Resources\Positions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Positions\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPositions extends ListRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
