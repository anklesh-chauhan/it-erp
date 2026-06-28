<?php

namespace App\Filament\Resources\ApprovalDelegations\Schemas;

use App\Models\ApprovalSetting;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApprovalDelegationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Delegation')
                    ->schema([
                        Select::make('delegator_user_id')
                            ->relationship('delegator', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('delegate_user_id')
                            ->relationship('delegate', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->different('delegator_user_id'),

                        Select::make('module')
                            ->options(ApprovalSetting::approvedModuleOptions())
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Toggle::make('active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Window')
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->nullable(),

                        DateTimePicker::make('ends_at')
                            ->rules(['nullable', 'date', 'after_or_equal:starts_at'])
                            ->nullable(),

                        Textarea::make('reason')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
