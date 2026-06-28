<?php

namespace App\Filament\Resources\Approvals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module')->badge(),
                TextColumn::make('record_type')
                    ->formatStateUsing(fn (?string $state): ?string => $state ? class_basename($state) : null),
                TextColumn::make('record_id'),
                TextColumn::make('requested_amount')->numeric(),
                TextColumn::make('territory.name')->label('Territory'),
                TextColumn::make('requester.name')->label('Requested By'),
                TextColumn::make('flow_version')->label('Version'),
                TextColumn::make('approval_status')
                    ->badge()
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'gray' => 'cancelled',
                    ]),
                TextColumn::make('finalized_at')->dateTime(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
