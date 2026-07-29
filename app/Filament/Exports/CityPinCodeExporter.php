<?php

namespace App\Filament\Exports;

use App\Models\CityPinCode;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CityPinCodeExporter extends Exporter
{
    protected static ?string $model = CityPinCode::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('area_town')
                ->label('Area / Town'),

            ExportColumn::make('pin_code')
                ->label('PIN Code'),

            ExportColumn::make('city.name')
                ->label('City'),

            ExportColumn::make('state.name')
                ->label('State'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your city pin code export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
