<?php

namespace App\Filament\Resources\CompanyMasterResource\Pages;

use App\Filament\Resources\CompanyMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMasters extends ListRecords
{
    protected static string $resource = CompanyMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
