<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CompanyConfig extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.company-config';
    protected static ?string $title = 'Company Configuration';
    protected static ?string $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 1001;
    protected static ?string $navigationLabel = 'Company Config';
}
