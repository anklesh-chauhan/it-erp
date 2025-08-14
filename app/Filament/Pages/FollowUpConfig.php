<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FollowUpConfig extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.follow-up-config';

    protected static ?string $title = 'Follow Up Configuration';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Follow Up Config';
}
