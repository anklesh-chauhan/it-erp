<?php

namespace App\Filament\Resources\ApprovalRules\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use App\Models\ApprovalSetting;
use App\Models\ApprovalRule;
use App\Models\User;

class ApprovalRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rule Details')
                    ->schema([
                        /*
                        |--------------------------------------------------------------------------
                        | MODULE
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Select::make('module')
                            ->label('Module')
                            ->options(ApprovalSetting::approvedModuleOptions())
                            ->required()
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {

                                // Reset approver when module changes
                                $set('approver_id', null);

                                if (!$state) {
                                    $set('level', 1);
                                    return;
                                }

                                // Set next available level
                                $lastLevel = ApprovalRule::where('module', $state)->max('level') ?? 0;
                                $set('level', $lastLevel + 1);
                            })
                            ->helperText('Only enabled modules from Approval Settings are shown.'),


                        /*
                        |--------------------------------------------------------------------------
                        | APPROVER
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Select::make('approver_id')
                            ->label('Approver User')
                            ->required()
                            ->searchable()
                            ->options(function (callable $get, $record) {

                                $module = $get('module');

                                if (!$module) {
                                    return User::pluck('name', 'id');
                                }

                                // Get approvers already used EXCEPT current record (edit mode)
                                $usedApprovers = ApprovalRule::where('module', $module)
                                    ->when($record?->id, fn ($q) => $q->where('id', '!=', $record->id))
                                    ->pluck('approver_id');

                                // List of users excluding used approvers
                                return User::whereNotIn('id', $usedApprovers)->pluck('name', 'id');
                            })
                            ->disabled(fn (callable $get) => !$get('module'))
                            ->helperText('Users already assigned to this module are excluded.'),


                        /*
                        |--------------------------------------------------------------------------
                        | LEVEL
                        |--------------------------------------------------------------------------
                        | Prevent duplicate levels + auto-correct on change
                        */
                        Forms\Components\TextInput::make('level')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {

                                $module = $get('module');
                                if (!$module || !$state) {
                                    return;
                                }

                                // Check if this level is already taken
                                $exists = ApprovalRule::where('module', $module)
                                    ->where('level', $state)
                                    ->when($record?->id, fn ($q) => $q->where('id', '!=', $record->id))
                                    ->exists();

                                if ($exists) {
                                    // Auto-fix level to next available value
                                    $next = (ApprovalRule::where('module', $module)->max('level') ?? 0) + 1;
                                    $set('level', $next);
                                }
                            })
                            ->helperText('Level auto-adjusts if already used for this module.'),


                        /*
                        |--------------------------------------------------------------------------
                        | TERRITORY
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Select::make('territory_id')
                            ->relationship('territory', 'name')
                            ->searchable()
                            ->nullable()
                            ->label('Territory (Optional)'),


                        Forms\Components\TextInput::make('min_amount')
                            ->label('Min Amount')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\TextInput::make('max_amount')
                            ->label('Max Amount')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\Toggle::make('active')
                            ->default(true),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
