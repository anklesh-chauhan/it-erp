<?php

namespace App\Filament\Resources\CompanyMasterTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CompanyMasterTypes\CompanyMasterTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMasterTypes extends ListRecords
{
    protected static string $resource = CompanyMasterTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
