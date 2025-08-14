<?php

namespace App\Filament\Resources\VisitRoutes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\VisitRoutes\VisitRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitRoute extends EditRecord
{
    protected static string $resource = VisitRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
