<?php

namespace App\Filament\Resources\AccountTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\AccountTypes\AccountTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountTypes extends ListRecords
{
    protected static string $resource = AccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
