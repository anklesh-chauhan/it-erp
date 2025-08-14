<?php

namespace App\Filament\Resources\ChartOfAccounts\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ChartOfAccounts\ChartOfAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChartOfAccount extends EditRecord
{
    protected static string $resource = ChartOfAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
