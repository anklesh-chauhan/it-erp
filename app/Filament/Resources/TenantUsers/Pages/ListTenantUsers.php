<?php

namespace App\Filament\Resources\TenantUsers\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TenantUsers\TenantUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenantUsers extends ListRecords
{
    protected static string $resource = TenantUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
