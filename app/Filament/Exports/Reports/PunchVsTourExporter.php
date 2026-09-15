<?php

namespace App\Filament\Exports\Reports;

use App\Models\PunchVsTourRow;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PunchVsTourExporter extends Exporter
{
    protected static ?string $model = PunchVsTourRow::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('activity_date')
                ->label('Date'),
            ExportColumn::make('mismatch')
                ->label('Exception'),
            ExportColumn::make('user.name')
                ->label('Employee'),
            ExportColumn::make('territory.name')
                ->label('Territory'),
            ExportColumn::make('first_punch_in')
                ->label('First in'),
            ExportColumn::make('last_punch_out')
                ->label('Last out'),
            ExportColumn::make('visit_status')
                ->label('Visit status'),
            ExportColumn::make('document_number')
                ->label('Visit number'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your punch vs tour export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
