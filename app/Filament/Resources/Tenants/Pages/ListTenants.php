<?php

namespace App\Filament\Resources\Tenants\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Tenants\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
