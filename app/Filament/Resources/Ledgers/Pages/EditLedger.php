<?php

namespace App\Filament\Resources\Ledgers\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Ledgers\LedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLedger extends EditRecord
{
    protected static string $resource = LedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
