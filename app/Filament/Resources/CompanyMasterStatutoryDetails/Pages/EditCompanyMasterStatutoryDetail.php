<?php

namespace App\Filament\Resources\CompanyMasterStatutoryDetailResource\Pages;

use App\Filament\Resources\CompanyMasterStatutoryDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMasterStatutoryDetail extends EditRecord
{
    protected static string $resource = CompanyMasterStatutoryDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
