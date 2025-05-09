<?php

namespace App\Filament\Tenant\Pages;
use App\Filament\Widgets\CurrentDatabase;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.tenant-dashboard';

    public static function getWidgets(): array
    {
        return [
            CurrentDatabase::class,
            // other widgets...
        ];
    }
}
