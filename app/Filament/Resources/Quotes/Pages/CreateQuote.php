<?php

namespace App\Filament\Resources\Quotes\Pages;

use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Filament\Forms\Components\ItemTable;
use Filament\Forms\Components\Section;
use App\Traits\SalesDocumentResourceTrait;

class CreateQuote extends CreateRecord
{
    use SalesDocumentResourceTrait;
    protected static string $resource = QuoteResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            static::getSalesDocumentPreferenceAction(), // ⚙ at top
        ];
    }
}
