<?php

namespace App\Filament\Exports\Reports;

use App\Models\SgipDistributionItem;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class DoctorSampleLedgerExporter extends Exporter
{
    protected static ?string $model = SgipDistributionItem::class;

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
            ExportColumn::make('item.item_type')
                ->label('Item type'),
            ExportColumn::make('item.item_name')
                ->label('Item'),
            ExportColumn::make('quantity')
                ->label('Quantity'),
            ExportColumn::make('total_value')
                ->label('Value'),
            ExportColumn::make('distribution.approval_status')
                ->label('Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your doctor sample ledger export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
