<?php

namespace App\Filament\Resources\StandardFareCharts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables;
use Filament\Tables\Table;

class StandardFareChartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fromCity.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('toCity.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('transportMode.name')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('distance_km')
                    ->label('Distance')
                    ->suffix(' km')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fare_amount')
                    ->money('INR') // Matches your locale
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('transport_mode_id')
                    ->relationship('transportMode', 'name'),
                Tables\Filters\SelectFilter::make('territory_id')
                    ->relationship('territory', 'name'),
                Tables\Filters\SelectFilter::make('from_city_id')
                    ->relationship('fromCity', 'name'),
                Tables\Filters\SelectFilter::make('to_city_id')
                    ->relationship('toCity', 'name'),
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
