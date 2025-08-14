<?php

namespace App\Filament\Resources\OrganizationalUnits\Pages;

use App\Filament\Resources\OrganizationalUnits\OrganizationalUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizationalUnit extends CreateRecord
{
    protected static string $resource = OrganizationalUnitResource::class;
}
