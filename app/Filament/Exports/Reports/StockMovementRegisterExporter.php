<?php

namespace App\Filament\Exports\Reports;

use App\Models\InventoryMovement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class StockMovementRegisterExporter extends Exporter
{
    protected static ?string $model = InventoryMovement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('movement_at')
                ->label('Moved at'),
            ExportColumn::make('item.item_code')
                ->label('Item code'),
            ExportColumn::make('item.item_name')
                ->label('Item'),
            ExportColumn::make('item.item_type')
                ->label('Item type'),
            ExportColumn::make('location.name')
                ->label('Location'),
            ExportColumn::make('movement_type')
                ->label('Movement'),
            ExportColumn::make('quantity_in')
                ->label('Qty in'),
            ExportColumn::make('quantity_out')
                ->label('Qty out'),
            ExportColumn::make('balance_after')
                ->label('Balance'),
            ExportColumn::make('remarks')
                ->label('Remarks'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your stock movement register export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
