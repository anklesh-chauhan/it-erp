<?php

namespace App\Filament\Resources\CompanyMasterBankDetailResource\Pages;

use App\Filament\Resources\CompanyMasterBankDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyMasterBankDetails extends ListRecords
{
    protected static string $resource = CompanyMasterBankDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
