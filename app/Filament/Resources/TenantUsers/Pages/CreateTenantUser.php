<?php

namespace App\Filament\Resources\TenantUsers\Pages;

use App\Filament\Resources\TenantUsers\TenantUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTenantUser extends CreateRecord
{
    protected static string $resource = TenantUserResource::class;
}
