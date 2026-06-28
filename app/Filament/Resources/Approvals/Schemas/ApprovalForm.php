<?php

namespace App\Filament\Resources\Approvals\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApprovalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Snapshot')
                    ->schema([
                        TextInput::make('module')->disabled()->dehydrated(false),
                        TextInput::make('record_type')->disabled()->dehydrated(false),
                        TextInput::make('record_id')->disabled()->dehydrated(false),
                        TextInput::make('requested_amount')->disabled()->dehydrated(false),
                        Select::make('territory_id')
                            ->relationship('territory', 'name')
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('requested_by')
                            ->relationship('requester', 'name')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Flow Snapshot')
                    ->schema([
                        Select::make('approval_flow_id')
                            ->relationship('flow', 'module')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('flow_version')->disabled()->dehydrated(false),
                        TextInput::make('approval_status')->disabled()->dehydrated(false),
                        DateTimePicker::make('completed_at')->disabled()->dehydrated(false),
                        DateTimePicker::make('finalized_at')->disabled()->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Selected Steps')
                    ->schema([
                        Textarea::make('selected_steps')
                            ->formatStateUsing(fn ($state): string => json_encode($state ?? [], JSON_PRETTY_PRINT))
                            ->rows(8)
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('selected_approvers')
                            ->formatStateUsing(fn ($state): string => json_encode($state ?? [], JSON_PRETTY_PRINT))
                            ->rows(8)
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Submitted Record')
                    ->schema([
                        Textarea::make('submitted_record_summary')
                            ->formatStateUsing(fn ($state): string => json_encode($state ?? [], JSON_PRETTY_PRINT))
                            ->rows(12)
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
