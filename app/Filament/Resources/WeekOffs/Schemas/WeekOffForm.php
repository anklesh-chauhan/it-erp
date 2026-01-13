<?php

namespace App\Filament\Resources\WeekOffs\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class WeekOffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Week Off Configuration')
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'first_name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Highest priority'),

                        Select::make('emp_department_id')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Applies if employee not specified'),

                        Select::make('shift_master_id')
                            ->relationship('shift', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Applies if employee & department not specified'),

                        Select::make('day_of_week')
                            ->required()
                            ->options([
                                0 => 'Sunday',
                                1 => 'Monday',
                                2 => 'Tuesday',
                                3 => 'Wednesday',
                                4 => 'Thursday',
                                5 => 'Friday',
                                6 => 'Saturday',
                            ]),

                        Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
