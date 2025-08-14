<?php

namespace App\Filament\Resources\CompanyMasters\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CompanyMasters\CompanyMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMaster extends EditRecord
{
    protected static string $resource = CompanyMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
