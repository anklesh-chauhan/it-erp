<?php

namespace App\Filament\Resources\CompanyMasterTypeResource\Pages;

use App\Filament\Resources\CompanyMasterTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyMasterType extends EditRecord
{
    protected static string $resource = CompanyMasterTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
