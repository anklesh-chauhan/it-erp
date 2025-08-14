<?php

namespace App\Filament\Resources\CompanyMasterBankDetails\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CompanyMasterBankDetails\CompanyMasterBankDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMasterBankDetail extends EditRecord
{
    protected static string $resource = CompanyMasterBankDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
