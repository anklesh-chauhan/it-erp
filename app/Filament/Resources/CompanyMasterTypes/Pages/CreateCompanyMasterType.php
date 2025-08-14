<?php

namespace App\Filament\Resources\CompanyMasterTypes\Pages;

use App\Filament\Resources\CompanyMasterTypes\CompanyMasterTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyMasterType extends CreateRecord
{
    protected static string $resource = CompanyMasterTypeResource::class;
}
