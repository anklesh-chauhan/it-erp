<?php

namespace App\Filament\Resources\VisitPurposes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\VisitPurposes\VisitPurposeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisitPurposes extends ListRecords
{
    protected static string $resource = VisitPurposeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
