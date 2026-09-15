<?php

namespace App\Filament\Exports\Reports;

use App\Models\Deal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DealPipelineExporter extends Exporter
{
    protected static ?string $model = Deal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('transaction_date')
                ->label('Date'),
            ExportColumn::make('reference_code')
                ->label('Reference'),
            ExportColumn::make('deal_name')
                ->label('Deal'),
            ExportColumn::make('owner.name')
                ->label('Owner'),
            ExportColumn::make('accountMaster.name')
                ->label('Account'),
            ExportColumn::make('status.name')
                ->label('Stage'),
            ExportColumn::make('amount')
                ->label('Amount'),
            ExportColumn::make('expected_revenue')
                ->label('Expected revenue'),
            ExportColumn::make('expected_close_date')
                ->label('Expected close'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your deal pipeline export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
