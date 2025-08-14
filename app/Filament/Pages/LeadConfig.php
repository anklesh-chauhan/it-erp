<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LeadConfig extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.pages.lead-config';
    protected static ?string $title = 'Lead Configuration';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Lead Config';
}
