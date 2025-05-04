<?php

namespace App\Filament\Resources\AccountMasterResource\Pages;

use App\Filament\Resources\AccountMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountMasters extends ListRecords
{
    protected static string $resource = AccountMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
