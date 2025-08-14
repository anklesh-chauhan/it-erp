<?php

namespace App\Filament\Resources\OrganizationalUnits\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\OrganizationalUnits\OrganizationalUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationalUnit extends EditRecord
{
    protected static string $resource = OrganizationalUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
