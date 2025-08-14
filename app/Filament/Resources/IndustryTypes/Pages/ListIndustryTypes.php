<?php

namespace App\Filament\Resources\IndustryTypeResource\Pages;

use App\Filament\Resources\IndustryTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndustryTypes extends ListRecords
{
    protected static string $resource = IndustryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
