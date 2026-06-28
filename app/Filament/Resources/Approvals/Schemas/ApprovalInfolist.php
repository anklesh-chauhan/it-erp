<?php

namespace App\Filament\Resources\Approvals\Schemas;

use Filament\Infolists\Components\CodeEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApprovalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Snapshot')
                    ->schema([
                        TextEntry::make('module')->badge(),
                        TextEntry::make('record_type')->formatStateUsing(fn (?string $state): ?string => $state ? class_basename($state) : null),
                        TextEntry::make('record_id'),
                        TextEntry::make('requested_amount')->money('INR'),
                        TextEntry::make('territory.name')->placeholder('-'),
                        TextEntry::make('requester.name')->label('Requested By')->placeholder('-'),
                    ])
                    ->columns(3),

                Section::make('Flow Snapshot')
                    ->schema([
                        TextEntry::make('flow.module')->label('Flow'),
                        TextEntry::make('flow_version'),
                        TextEntry::make('approval_status')->badge(),
                        TextEntry::make('completed_at')->dateTime()->placeholder('-'),
                        TextEntry::make('finalized_at')->dateTime()->placeholder('-'),
                    ])
                    ->columns(3),

                Section::make('Selected Routing')
                    ->schema([
                        CodeEntry::make('selected_steps')->label('Steps'),
                        CodeEntry::make('selected_approvers')->label('Approvers'),
                    ])
                    ->columns(2),

                Section::make('Submitted Record')
                    ->schema([
                        CodeEntry::make('submitted_record_summary')
                            ->label('Summary'),
                    ]),

                Section::make('Activity')
                    ->schema([
                        RepeatableEntry::make('activities')
                            ->schema([
                                TextEntry::make('created_at')->dateTime(),
                                TextEntry::make('action')->badge(),
                                TextEntry::make('actor.name')->placeholder('-'),
                                TextEntry::make('from_status')->placeholder('-'),
                                TextEntry::make('to_status')->placeholder('-'),
                                TextEntry::make('comments')->placeholder('-'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
