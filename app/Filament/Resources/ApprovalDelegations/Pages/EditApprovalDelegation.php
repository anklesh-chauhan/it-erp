<?php

namespace App\Filament\Resources\ApprovalDelegations\Pages;

use App\Filament\Resources\ApprovalDelegations\ApprovalDelegationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApprovalDelegation extends EditRecord
{
    protected static string $resource = ApprovalDelegationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
