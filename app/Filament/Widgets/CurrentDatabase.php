<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class CurrentDatabase extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Tenant DB', DB::connection()->getDatabaseName()),
        ];
    }
}
