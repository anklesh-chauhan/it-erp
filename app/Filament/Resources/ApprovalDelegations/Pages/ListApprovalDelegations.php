<?php

namespace App\Filament\Resources\ApprovalDelegations\Pages;

use App\Filament\Resources\ApprovalDelegations\ApprovalDelegationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApprovalDelegations extends ListRecords
{
    protected static string $resource = ApprovalDelegationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
