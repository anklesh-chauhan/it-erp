<?php

namespace App\Filament\Exports\Reports;

use App\Models\SalesDocumentRegisterRow;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SalesDocumentRegisterExporter extends Exporter
{
    protected static ?string $model = SalesDocumentRegisterRow::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('date')
                ->label('Date'),
            ExportColumn::make('document_type')
                ->label('Type'),
            ExportColumn::make('document_number')
                ->label('Number'),
            ExportColumn::make('accountMaster.name')
                ->label('Account'),
            ExportColumn::make('salesPerson.name')
                ->label('Sales person'),
            ExportColumn::make('status')
                ->label('Status'),
            ExportColumn::make('total')
                ->label('Total'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your sales document register export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
