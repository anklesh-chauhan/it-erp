<?php

namespace App\Filament\Resources\LeaveApplications\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Placeholder;

class LeaveApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('leave_type_id')
                    ->label('Leave Type')
                    ->required()
                    ->options(
                        LeaveType::query()
                            ->pluck('name', 'id')
                    )
                    ->reactive(),

                DatePicker::make('from_date')
                    ->required()
                    ->reactive(),

                DatePicker::make('to_date')
                    ->required()
                    ->reactive()
                    ->afterOrEqual('from_date'),

                Toggle::make('is_half_day')
                    ->label('Half Day')
                    ->reactive(),

                Select::make('half_day_type')
                    ->visible(fn ($get) => $get('is_half_day'))
                    ->required(fn ($get) => $get('is_half_day'))
                    ->options([
                        'first_half' => 'First Half',
                        'second_half' => 'Second Half',
                    ]),

                Textarea::make('reason')
                    ->label('Reason')
                    ->rows(3),

                Select::make('substitute_user_id')
                    ->label('Substitute Employee')
                    ->searchable()
                    ->options(
                        \App\Models\User::query()
                            ->where('id', '!=', Auth::id())
                            ->pluck('name', 'id')
                    ),

                Placeholder::make('leave_balance')
                    ->label('Available Balance')
                    ->content(function ($get) {
                        if (! $get('leave_type_id')) {
                            return '—';
                        }

                        return app(\App\Services\Attendance\LeaveBalanceCalculator::class)
                            ->calculate(
                                employeeId: Auth::id(),
                                leaveTypeId: $get('leave_type_id')
                            )['closing'] ?? '—';
                    }),
            ]);
    }
}
