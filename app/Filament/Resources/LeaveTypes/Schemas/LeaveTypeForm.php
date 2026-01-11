<?php

namespace App\Filament\Resources\LeaveTypes\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use App\Models\EmployeeAttendanceStatus;

class LeaveTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->helperText('Example: CL, SL, PL, LWP'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                    ])
                    ->columns(2),

                Section::make('Payroll & Attendance')
                    ->schema([
                        Toggle::make('is_paid')
                            ->label('Paid Leave')
                            ->default(true),

                        Toggle::make('affects_payroll')
                            ->label('Affects Payroll')
                            ->default(true),

                        Select::make('employee_attendance_status_id')
                            ->label('Attendance Status')
                            ->relationship('employeeAttendanceStatus', 'status')
                            ->searchable()
                            ->preload()
                            ->helperText('Attendance status to be marked when this leave is applied'),
                    ])
                    ->columns(2),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }
}
