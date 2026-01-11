<?php

namespace App\Filament\Resources\LeaveAdjustments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

class LeaveAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')->relationship('employee', 'first_name')->required(),
                Select::make('leave_type_id')->relationship('leaveType', 'name')->required(),
                Select::make('type')->options([
                    'positive' => 'Credit',
                    'negative' => 'Debit',
                ])->required(),
                TextInput::make('days')->numeric()->required(),
                DatePicker::make('effective_date')->required(),
                Textarea::make('reason'),
            ]);
    }
}
