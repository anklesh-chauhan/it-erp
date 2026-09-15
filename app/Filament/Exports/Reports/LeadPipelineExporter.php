<?php

namespace App\Filament\Exports\Reports;

use App\Models\Lead;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class LeadPipelineExporter extends Exporter
{
    protected static ?string $model = Lead::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('transaction_date')
                ->label('Date'),
            ExportColumn::make('reference_code')
                ->label('Reference'),
            ExportColumn::make('owner.name')
                ->label('Owner'),
            ExportColumn::make('territory.name')
                ->label('Territory'),
            ExportColumn::make('accountMaster.name')
                ->label('Account'),
            ExportColumn::make('status.name')
                ->label('Status'),
            ExportColumn::make('annual_revenue')
                ->label('Value'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your lead pipeline export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
