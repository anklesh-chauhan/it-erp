<?php

namespace App\Filament\Resources\CompanyMasterStatutoryDetails\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CompanyMasterStatutoryDetails\CompanyMasterStatutoryDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMasterStatutoryDetails extends ListRecords
{
    protected static string $resource = CompanyMasterStatutoryDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
