<?php

namespace App\Filament\Resources\CompanyMasterBankDetailResource\Pages;

use App\Filament\Resources\CompanyMasterBankDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMasterBankDetail extends EditRecord
{
    protected static string $resource = CompanyMasterBankDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
