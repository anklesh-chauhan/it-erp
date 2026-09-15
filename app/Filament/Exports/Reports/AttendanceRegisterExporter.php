<?php

namespace App\Filament\Exports\Reports;

use App\Models\DailyAttendance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class AttendanceRegisterExporter extends Exporter
{
    protected static ?string $model = DailyAttendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('attendance_date')
                ->label('Date'),
            ExportColumn::make('employee.full_name')
                ->label('Employee'),
            ExportColumn::make('shift.name')
                ->label('Shift'),
            ExportColumn::make('first_punch_in')
                ->label('First in'),
            ExportColumn::make('last_punch_out')
                ->label('Last out'),
            ExportColumn::make('status.status')
                ->label('Status'),
            ExportColumn::make('actual_working_minutes')
                ->label('Work minutes'),
            ExportColumn::make('is_absent')
                ->label('Absent'),
            ExportColumn::make('is_half_day')
                ->label('Half day'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attendance register export has completed and '.Number::format($export->successful_rows).' '
            .str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
