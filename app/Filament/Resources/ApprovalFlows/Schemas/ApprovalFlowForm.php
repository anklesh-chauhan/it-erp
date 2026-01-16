<?php

namespace App\Filament\Resources\ApprovalFlows\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use App\Models\ApprovalSetting;

class ApprovalFlowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Flow Conditions')
                ->schema([
                    Select::make('module')
                        ->label('Module')
                            ->options(ApprovalSetting::approvedModuleOptions())
                            ->required()
                            ->preload()
                            ->searchable()
                            ->live()
                            ->helperText('Only enabled modules from Approval Settings are shown.'),

                    Select::make('territory_id')
                        ->relationship('territory', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    TextInput::make('min_amount')->numeric()->nullable(),
                    TextInput::make('max_amount')->numeric()->nullable(),

                    Toggle::make('active')->default(true),
                ])
                ->columns(2),
            ]);
    }
}
