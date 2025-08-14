<?php

namespace App\Filament\Resources\CompanyMasterTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CompanyMasterTypes\CompanyMasterTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMasterType extends EditRecord
{
    protected static string $resource = CompanyMasterTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
