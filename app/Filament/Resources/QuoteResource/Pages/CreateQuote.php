<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Filament\Forms\Components\ItemTable;
use Filament\Forms\Components\Section;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

}
