<?php

namespace App\Filament\Resources\VisitTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\VisitTypes\VisitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisitTypes extends ListRecords
{
    protected static string $resource = VisitTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
