<?php

namespace App\Filament\Resources\Quotes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\SalesDocumentResourceTrait;

class EditQuote extends EditRecord
{
    use SalesDocumentResourceTrait;
    
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
