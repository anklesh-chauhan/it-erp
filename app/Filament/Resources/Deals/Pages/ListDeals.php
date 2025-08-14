<?php

namespace App\Filament\Resources\Deals\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Deals\DealResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeals extends ListRecords
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
