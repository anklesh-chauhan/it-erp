<?php

namespace App\Filament\Exports\Reports;

use App\Models\FollowUp;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class OverdueFollowUpExporter extends Exporter
{
    protected static ?string $model = FollowUp::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('follow_up_date')
                ->label('Follow-up date'),
            ExportColumn::make('next_follow_up_date')
                ->label('Next follow-up'),
            ExportColumn::make('user.name')
                ->label('Assigned to'),
            ExportColumn::make('followupable_type')
                ->label('Related type'),
            ExportColumn::make('status.name')
                ->label('Status'),
            ExportColumn::make('priority.name')
                ->label('Priority'),
            ExportColumn::make('interaction')
                ->label('Interaction'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your overdue follow-up export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
