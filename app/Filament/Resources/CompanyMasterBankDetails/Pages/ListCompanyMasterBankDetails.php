<?php

namespace App\Filament\Resources\CompanyMasterBankDetails\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CompanyMasterBankDetails\CompanyMasterBankDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMasterBankDetails extends ListRecords
{
    protected static string $resource = CompanyMasterBankDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
