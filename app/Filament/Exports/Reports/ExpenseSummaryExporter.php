<?php

namespace App\Filament\Exports\Reports;

use App\Models\SalesDcrExpense;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ExpenseSummaryExporter extends Exporter
{
    protected static ?string $model = SalesDcrExpense::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('salesDcr.dcr_date')
                ->label('DCR date'),
            ExportColumn::make('salesDcr.user.name')
                ->label('Employee'),
            ExportColumn::make('salesDcr.territory.name')
                ->label('Territory'),
            ExportColumn::make('expenseType.name')
                ->label('Expense type'),
            ExportColumn::make('amount')
                ->label('Amount'),
            ExportColumn::make('is_auto_calculated')
                ->label('Auto calculated'),
            ExportColumn::make('salesDcr.approval_status')
                ->label('DCR status'),
            ExportColumn::make('salesDcr.total_expense_approved')
                ->label('DCR approved expense'),
            ExportColumn::make('salesDcr.total_expense_rejected')
                ->label('DCR rejected expense'),
            ExportColumn::make('remarks')
                ->label('Remarks'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your expense summary export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
