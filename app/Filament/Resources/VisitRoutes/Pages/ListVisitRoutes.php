<?php

namespace App\Filament\Resources\VisitRoutes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\VisitRoutes\VisitRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisitRoutes extends ListRecords
{
    protected static string $resource = VisitRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
