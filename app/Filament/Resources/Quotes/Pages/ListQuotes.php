<?php

namespace App\Filament\Resources\Quotes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
