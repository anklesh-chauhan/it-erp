<?php

namespace App\Filament\Resources\ShiftMasters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ShiftMastersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('shift_type'),

                TextColumn::make('start_time')
                    ->time('H:i'),

                TextColumn::make('end_time')
                    ->time('H:i'),

                IconColumn::make('is_night_shift')
                    ->boolean(),

                IconColumn::make('is_flexible')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('shift_type')
                    ->options([
                        'fixed' => 'Fixed',
                        'rotational' => 'Rotational',
                    ]),

                TernaryFilter::make('is_night_shift')
                    ->label('Night Shift'),
            ])
            ->recordActions([
                EditAction::make()->visible(fn ($record) => ! $record->is_system),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
