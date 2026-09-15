<?php

namespace App\Filament\Exports\Reports;

use App\Models\SalesDcr;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DcrRegisterExporter extends Exporter
{
    protected static ?string $model = SalesDcr::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('dcr_date')
                ->label('DCR date'),
            ExportColumn::make('user.name')
                ->label('Employee'),
            ExportColumn::make('territory.name')
                ->label('Territory'),
            ExportColumn::make('visits_count')
                ->label('Visits'),
            ExportColumn::make('distance_covered')
                ->label('Distance (km)'),
            ExportColumn::make('total_expense')
                ->label('Expense'),
            ExportColumn::make('total_expense_approved')
                ->label('Approved expense'),
            ExportColumn::make('total_expense_rejected')
                ->label('Rejected expense'),
            ExportColumn::make('approval_status')
                ->label('Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your DCR register export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
