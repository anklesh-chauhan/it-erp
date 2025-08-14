<?php

namespace App\Filament\Landlord\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.landlord.pages.landlord-dashboard';
}
