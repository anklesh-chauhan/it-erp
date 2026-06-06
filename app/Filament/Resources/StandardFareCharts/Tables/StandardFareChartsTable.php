<?php

namespace App\Filament\Resources\StandardFareCharts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StandardFareChartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromAreaTown.full_location')
                    ->label('From')
                    ->sortable()
                    ->searchable(
                        query: function ($query, string $search): void {
                            $query->whereHas(
                                'fromAreaTown',
                                fn ($q) => $q->searchLocation($search)
                            );
                        }
                    )
                    ->wrap()
                    ->tooltip(fn ($record) => $record->fromAreaTown?->full_location),

                Tables\Columns\TextColumn::make('toAreaTown.full_location')
                    ->label('To')
                    ->sortable()
                    ->searchable(
                        query: function ($query, string $search): void {
                            $query->whereHas(
                                'toAreaTown',
                                fn ($q) => $q->searchLocation($search)
                            );
                        }
                    )
                    ->wrap()
                    ->tooltip(fn ($record) => $record->toAreaTown?->full_location),

                Tables\Columns\TextColumn::make('distance_km')
                    ->label('Distance')
                    ->suffix(' km')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('fare_amount')
                    ->label('Fare')
                    ->money('INR')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('typeMaster.name')
                    ->label('SFC Type')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('territory.name')
                    ->label('Territory')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('patch.name')
                    ->label('Patch')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('territory_id')
                    ->relationship('territory', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
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
