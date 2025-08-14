<?php

namespace App\Filament\Resources\CustomerInteractionResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CustomerInteractionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerInteraction extends EditRecord
{
    protected static string $resource = CustomerInteractionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
