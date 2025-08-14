<?php

namespace App\Filament\Resources\CompanyMasterStatutoryDetails\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CompanyMasterStatutoryDetails\CompanyMasterStatutoryDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMasterStatutoryDetail extends EditRecord
{
    protected static string $resource = CompanyMasterStatutoryDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
