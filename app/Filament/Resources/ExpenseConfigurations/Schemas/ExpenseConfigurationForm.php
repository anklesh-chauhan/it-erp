<?php

namespace App\Filament\Resources\ExpenseConfigurations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class ExpenseConfigurationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // Main Configuration Area
                        Group::make()
                            ->schema([
                                Section::make('Scope & Assignment')
                                    ->description('Define who and where this expense rule applies to.')
                                    ->schema([
                                        Select::make('expense_type_id')
                                            ->relationship('expenseType', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        Grid::make(2)->schema([
                                            Select::make('job_role_id')
                                                ->relationship('jobRole', 'name')
                                                ->nullable(),
                                            Select::make('position_id')
                                                ->relationship('position', 'name')
                                                ->nullable(),
                                            Select::make('territory_id')
                                                ->relationship('territory', 'name')
                                                ->nullable(),
                                            Select::make('city_id')
                                                ->relationship('city', 'name')
                                                ->nullable(),
                                        ]),

                                        Select::make('mode_of_transport_id')
                                            ->relationship('transportMode', 'name')
                                            ->placeholder('All transport modes')
                                            ->nullable(),
                                    ])->collapsible(),

                                Section::make('Financial Logic')
                                    ->description('Set how the expense amount is calculated.')
                                    ->schema([
                                        Select::make('calculation_type')
                                            ->options([
                                                'fixed' => 'Fixed Amount',
                                                'per_km' => 'Rate per KM',
                                                'per_day' => 'Daily Allowance',
                                                'per_visit' => 'Per Visit Fee',
                                                'manual' => 'User Defined (Manual)',
                                            ])
                                            ->required()
                                            ->live(), // Key for reactive changes

                                        Grid::make(3)->schema([
                                            TextInput::make('rate')
                                                ->numeric()
                                                ->prefix('$') // Or your currency symbol
                                                ->hidden(fn ($get) => $get('calculation_type') === 'manual'),

                                            TextInput::make('min_amount')
                                                ->label('Floor')
                                                ->numeric()
                                                ->helperText('Minimum payable'),

                                            TextInput::make('max_amount')
                                                ->label('Ceiling')
                                                ->numeric()
                                                ->helperText('Maximum limit'),
                                        ]),
                                    ]),
                            ])->columnSpan(2),

                        // Sidebar Area (Settings & Status)
                        Group::make()
                            ->schema([
                                Section::make('Compliance & Rules')
                                    ->schema([
                                        Toggle::make('requires_attachment')
                                            ->label('Receipt Required'),
                                        Toggle::make('requires_approval')
                                            ->label('Needs Manager Sign-off'),
                                        Toggle::make('allow_manual_override')
                                            ->default(true),
                                    ]),

                                Section::make('Validity Period')
                                    ->schema([
                                        DatePicker::make('effective_from')
                                            ->required()
                                            ->native(false),
                                        DatePicker::make('effective_to')
                                            ->native(false),
                                        Toggle::make('is_active')
                                            ->label('Active Status')
                                            ->default(true),
                                    ]),
                            ])->columnSpan(1),
                    ])->columnSpanFull(),
            ]);
    }
}
