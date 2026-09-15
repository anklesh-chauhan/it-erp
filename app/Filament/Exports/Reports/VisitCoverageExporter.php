<?php

namespace App\Filament\Exports\Reports;

use App\Models\SalesTourPlanDetail;
use App\Services\Reports\VisitCoverageCalculator;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class VisitCoverageExporter extends Exporter
{
    protected static ?string $model = SalesTourPlanDetail::class;

    public static function getColumns(): array
    {
        $calculator = app(VisitCoverageCalculator::class);

        return [
            ExportColumn::make('date')
                ->label('Date'),
            ExportColumn::make('tourPlan.user.name')
                ->label('Employee'),
            ExportColumn::make('territory.name')
                ->label('Territory'),
            ExportColumn::make('visitType.name')
                ->label('Visit type'),
            ExportColumn::make('planned_accounts')
                ->label('Planned accounts')
                ->getStateUsing(fn (SalesTourPlanDetail $record): int => $calculator->forDetail($record)['planned']),
            ExportColumn::make('completed_accounts')
                ->label('Completed accounts')
                ->getStateUsing(fn (SalesTourPlanDetail $record): int => $calculator->forDetail($record)['completed']),
            ExportColumn::make('visits')
                ->label('Visits')
                ->getStateUsing(fn (SalesTourPlanDetail $record): int => $calculator->forDetail($record)['visits']),
            ExportColumn::make('coverage_percent')
                ->label('Coverage %')
                ->getStateUsing(fn (SalesTourPlanDetail $record): float => $calculator->forDetail($record)['percentage']),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your visit coverage export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
