<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SalesMarketingConfig extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.sales-marketing-config';
    protected static ?string $title = 'Sales & Marketing Configuration';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 1000;
    protected static ?string $navigationLabel = 'Sales & Marketing';
}
