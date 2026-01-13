<?php

namespace App\Filament\Imports;

use App\Models\Holiday;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class HolidayImporter extends Importer
{
    protected static ?string $model = Holiday::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('country')
                ->relationship(),
            ImportColumn::make('state')
                ->relationship(),
            ImportColumn::make('location')
                ->relationship(),
            ImportColumn::make('is_optional')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('is_active')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): Holiday
    {
        return Holiday::firstOrNew([
            'date' => $this->data['date'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your holiday import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
