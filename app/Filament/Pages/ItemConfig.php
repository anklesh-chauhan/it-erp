<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ItemConfig extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.item-config';
    protected static ?string $title = 'Item Configuration';
    protected static ?string $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 1003;
    protected static ?string $navigationLabel = 'Items';

}
