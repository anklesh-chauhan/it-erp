<?php

namespace App\Filament\Resources\LeaveRules\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;

class LeaveRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')->schema([
                    TextInput::make('rule_key')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Select::make('leave_rule_category_id')
                        ->relationship('category', 'name')
                        ->required(),

                    Select::make('leave_type_id')
                        ->relationship('leaveType', 'name')
                        ->nullable(),

                    Select::make('employee_attendance_status_id')
                        ->relationship('attendanceStatus', 'status')
                        ->nullable(),

                    TextInput::make('name')->required(),
                    Textarea::make('description'),
                ]),

                Section::make('Rule Logic')->schema([
                    KeyValue::make('condition_json')
                        ->label('Condition JSON')
                        ->addable()
                        ->deletable(),

                    KeyValue::make('action_json')
                        ->label('Action JSON')
                        ->addable()
                        ->deletable(),
                ]),

                Section::make('Execution')->schema([
                    TextInput::make('priority')
                        ->numeric()
                        ->default(100),

                    Toggle::make('is_active')
                        ->default(true),
                ]),
            ]);
    }
}
