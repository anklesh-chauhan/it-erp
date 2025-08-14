<?php

namespace App\Filament\Resources\Deals\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Deals\DealResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeal extends EditRecord
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
