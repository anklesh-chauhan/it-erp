<?php

namespace App\Filament\Resources\TenantUsers\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TenantUsers\TenantUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenantUser extends EditRecord
{
    protected static string $resource = TenantUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
