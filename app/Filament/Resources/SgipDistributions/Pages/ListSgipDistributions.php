<?php

namespace App\Filament\Resources\SgipDistributions\Pages;

use App\Filament\Resources\SgipDistributions\SgipDistributionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSgipDistributions extends ListRecords
{
    protected static string $resource = SgipDistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
