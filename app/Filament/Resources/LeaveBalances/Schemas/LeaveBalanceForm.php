<?php

namespace App\Filament\Resources\LeaveBalances\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class LeaveBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')->relationship('employee', 'first_name')->required(),
                Select::make('leave_type_id')->relationship('leaveType', 'name')->required(),
                TextInput::make('opening_balance')->numeric()->required(),
                DatePicker::make('year_start_date')->required(),
                DatePicker::make('year_end_date')->required(),
            ]);
    }
}
