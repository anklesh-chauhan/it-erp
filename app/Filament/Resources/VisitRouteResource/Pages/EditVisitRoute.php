<?php

namespace App\Filament\Resources\VisitRouteResource\Pages;

use App\Filament\Resources\VisitRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitRoute extends EditRecord
{
    protected static string $resource = VisitRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
