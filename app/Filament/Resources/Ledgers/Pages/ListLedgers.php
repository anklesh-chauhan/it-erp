<?php

namespace App\Filament\Resources\Ledgers\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Ledgers\LedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLedgers extends ListRecords
{
    protected static string $resource = LedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
