<?php

namespace App\Filament\Resources\Holidays\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class HolidayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Holiday Details')
                    ->schema([
                        DatePicker::make('date')
                            ->required()
                            ->unique(
                                table: 'holidays',
                                column: 'date',
                                ignorable: fn ($record) => $record,
                                modifyRuleUsing: function ($rule, callable $get) {
                                    return $rule->where(
                                        fn ($q) => $q
                                            ->where('country_id', $get('country_id'))
                                            ->where('state_id', $get('state_id'))
                                            ->where('location_master_id', $get('location_master_id'))
                                    );
                                }
                            ),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Select::make('country_id')
                            ->relationship('country', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Select::make('state_id')
                            ->relationship('state', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Select::make('location_master_id')
                            ->relationship('location', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Toggle::make('is_optional')
                            ->label('Optional Holiday'),

                        Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
