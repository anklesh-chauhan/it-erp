<?php

namespace App\Filament\Resources\AccountMasters\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\AccountMasters\AccountMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountMasters extends ListRecords
{
    protected static string $resource = AccountMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
