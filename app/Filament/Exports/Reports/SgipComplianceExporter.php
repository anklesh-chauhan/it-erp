<?php

namespace App\Filament\Exports\Reports;

use App\Models\SgipViolation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SgipComplianceExporter extends Exporter
{
    protected static ?string $model = SgipViolation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('distribution.visit_date')
                ->label('Visit date'),
            ExportColumn::make('distribution.doctor.name')
                ->label('Doctor'),
            ExportColumn::make('distribution.user.name')
                ->label('Employee'),
            ExportColumn::make('distribution.territory.name')
                ->label('Territory'),
            ExportColumn::make('distribution.marketingCampaign.name')
                ->label('Campaign'),
            ExportColumn::make('violation_type')
                ->label('Violation'),
            ExportColumn::make('allowed_value')
                ->label('Allowed'),
            ExportColumn::make('actual_value')
                ->label('Actual'),
            ExportColumn::make('limit.period')
                ->label('Limit period'),
            ExportColumn::make('distribution.approval_status')
                ->label('Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your SGIP compliance export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
