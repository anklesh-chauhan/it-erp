<?php

namespace App\Filament\Resources\SgipLimits\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\AccountMaster;
use App\Models\Employee;
use App\Models\Territory;

class SgipLimitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Applicability')
                    ->columns(2)
                    ->schema([
                        Select::make('applies_to')
                            ->label('Applies To')
                            ->options([
                                'global'    => 'Global',
                                'account'   => 'Doctor',
                                'employee'  => 'Employee',
                                'territory' => 'Territory',
                            ])
                            ->required()
                            ->reactive(),

                        Select::make('applies_to_id')
                            ->label('Target')
                            ->options(function (Get $get) {
                                return match ($get('applies_to')) {
                                    'account' => AccountMaster::pluck('name', 'id'),
                                    'employee' => Employee::pluck('first_name', 'id'),
                                    'territory' => Territory::pluck('name', 'id'),
                                    default => [],
                                };
                            })
                            ->visible(fn (Get $get) => $get('applies_to') !== 'global')
                            ->required(fn (Get $get) => $get('applies_to') !== 'global')
                            ->searchable(),
                    ]),

                Section::make('Limit Definition')
                    ->columns(2)
                    ->schema([
                        Select::make('item_type')
                            ->label('Item Type')
                            ->options([
                                'sample' => 'Sample',
                                'gift'   => 'Gift',
                                'input'  => 'Promotional Input',
                            ])
                            ->required(),

                        Select::make('period')
                            ->label('Period')
                            ->options([
                                'daily'   => 'Daily',
                                'monthly' => 'Monthly',
                                'yearly'  => 'Yearly',
                            ])
                            ->required(),

                        TextInput::make('max_quantity')
                            ->numeric()
                            ->nullable()
                            ->minValue(0)
                            ->helperText('Leave empty if quantity is not restricted'),

                        TextInput::make('max_value')
                            ->numeric()
                            ->nullable()
                            ->minValue(0)
                            ->prefix('₹')
                            ->helperText('Leave empty if value is not restricted'),
                    ]),
            ]);
    }
}
