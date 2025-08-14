<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.pages.admin-dashboard';


}
