<?php

namespace App\Filament\Resources\ExpenseConfigurations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExpenseConfigurationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('expense_type_id')
                    ->relationship('expenseType', 'name')
                    ->required(),

                Select::make('mode_of_transport_id')
                    ->relationship('transportMode', 'name')
                    ->nullable(),

                Select::make('calculation_type')
                    ->options([
                        'fixed' => 'Fixed',
                        'per_km' => 'Per KM',
                        'per_day' => 'Per Day',
                        'per_visit' => 'Per Visit',
                        'manual' => 'Manual',
                    ])
                    ->required(),

                TextInput::make('rate')->numeric(),
                TextInput::make('min_amount')->numeric(),
                TextInput::make('max_amount')->numeric(),

                Toggle::make('requires_attachment'),
                Toggle::make('requires_approval'),
                Toggle::make('allow_manual_override')->default(true),

                DatePicker::make('effective_from')->required(),
                DatePicker::make('effective_to'),

                Toggle::make('is_active')->default(true),
            ]);
    }
}
