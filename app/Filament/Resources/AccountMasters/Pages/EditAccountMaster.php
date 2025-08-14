<?php

namespace App\Filament\Resources\AccountMasters\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AccountMasters\AccountMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountMaster extends EditRecord
{
    protected static string $resource = AccountMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
