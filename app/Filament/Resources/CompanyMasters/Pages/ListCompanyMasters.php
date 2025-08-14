<?php

namespace App\Filament\Resources\CompanyMasters\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CompanyMasters\CompanyMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMasters extends ListRecords
{
    protected static string $resource = CompanyMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
