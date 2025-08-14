<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class CurrentDatabase extends BaseWidget
{
    protected string $view = 'filament.widgets.current-database';

    protected function getViewData(): array
    {
        return [
            'databaseName' => DB::connection()->getDatabaseName(),
        ];
    }
}
