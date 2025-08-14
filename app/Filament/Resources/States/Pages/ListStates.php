<?php

namespace App\Filament\Resources\States\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\States\StateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStates extends ListRecords
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
