<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\MyApprovals;
use App\Filament\Pages\Reports\AttendanceRegister;
use App\Filament\Pages\Reports\DcrRegister;
use App\Filament\Pages\TodaysTour;
use App\Services\Reports\DashboardMetrics;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Throwable;

class FieldActivityStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Today';

    protected ?string $description = 'Field activity, DCRs, approvals, and punch-in. Counts follow your record visibility.';

    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $metrics = app(DashboardMetrics::class)->forUser(Auth::user());

        return [
            Stat::make('Today’s visits', $metrics->visitsToday)
                ->description('Not cancelled')
                ->url($this->safeUrl(fn (): string => TodaysTour::getUrl())),

            Stat::make('Pending DCRs', $metrics->pendingDcrs)
                ->description('Draft or awaiting approval')
                ->url($this->safeUrl(fn (): string => DcrRegister::getUrl())),

            Stat::make('My approvals', $metrics->pendingApprovals)
                ->description('Waiting on you')
                ->url($this->safeUrl(fn (): string => MyApprovals::getUrl())),

            Stat::make('Punched in today', $metrics->punchedInToday)
                ->description('Team attendance')
                ->url($this->safeUrl(fn (): string => AttendanceRegister::getUrl())),
        ];
    }

    protected function safeUrl(callable $callback): ?string
    {
        try {
            return $callback();
        } catch (Throwable) {
            return null;
        }
    }
}
