<?php

namespace App\Filament\Resources\CustomerInteractionResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\CustomerInteractionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerInteractions extends ListRecords
{
    protected static string $resource = CustomerInteractionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
