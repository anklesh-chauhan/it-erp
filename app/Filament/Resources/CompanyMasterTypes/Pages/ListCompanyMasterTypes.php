<?php

namespace App\Filament\Resources\CompanyMasterTypeResource\Pages;

use App\Filament\Resources\CompanyMasterTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMasterTypes extends ListRecords
{
    protected static string $resource = CompanyMasterTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
