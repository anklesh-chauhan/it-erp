<?php

namespace App\Filament\Resources\VisitRouteResource\Pages;

use App\Filament\Resources\VisitRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisitRoutes extends ListRecords
{
    protected static string $resource = VisitRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
