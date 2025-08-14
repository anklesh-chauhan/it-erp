<?php

namespace App\Filament\Resources\CompanyMasters\Pages;

use App\Filament\Resources\CompanyMasters\CompanyMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyMaster extends CreateRecord
{
    protected static string $resource = CompanyMasterResource::class;
}
