<?php

namespace App\Filament\Resources\DailyAttendances\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class DailyAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Employee & Date')
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'employee_id')
                            ->searchable()
                            ->required(),

                        DatePicker::make('attendance_date')
                            ->required(),

                        Select::make('shift_master_id')
                            ->relationship('shift', 'name')
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Punch Summary')
                    ->schema([
                        TimePicker::make('first_punch_in')
                            ->seconds(false)
                            ->format('H:i')
                            ->disabled(),

                        TimePicker::make('last_punch_out')
                            ->seconds(false)
                            ->format('H:i')
                            ->disabled(),

                        TextInput::make('actual_working_minutes')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(3),

                Section::make('Calculated Metrics')
                    ->schema([
                        TextInput::make('late_in_minutes')->numeric()->disabled(),
                        TextInput::make('early_out_minutes')->numeric()->disabled(),
                        TextInput::make('late_out_minutes')->numeric()->disabled(),
                        TextInput::make('early_in_minutes')->numeric()->disabled(),
                    ])
                    ->columns(4),

                Section::make('Result')
                    ->schema([
                        TextInput::make('final_working_minutes')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('late_mark_count')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('ot_minutes')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('comp_off_days')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(4),

                Section::make('Flags & Status')
                    ->schema([
                        Toggle::make('is_half_day')->disabled(),
                        Toggle::make('is_absent')->disabled(),
                        Toggle::make('is_weekly_off')->disabled(),
                        Toggle::make('is_paid_holiday')->disabled(),

                        Select::make('status_id')
                            ->relationship('status', 'status')
                            ->disabled(),
                    ])
                    ->columns(3),
            ])->columns(1);
    }
}
