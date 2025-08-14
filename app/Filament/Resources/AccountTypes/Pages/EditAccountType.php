<?php

namespace App\Filament\Resources\AccountTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\AccountTypes\AccountTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountType extends EditRecord
{
    protected static string $resource = AccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
