<?php

namespace App\Filament\Resources\TravelSegments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TravelSegmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('salesDcr.id')
                    ->searchable(),
                TextColumn::make('visit.id')
                    ->searchable(),
                TextColumn::make('salesTourPlanDetail.id')
                    ->searchable(),
                TextColumn::make('patch.name')
                    ->searchable(),
                TextColumn::make('fromAccount.name')
                    ->searchable(),
                TextColumn::make('toAccount.name')
                    ->searchable(),
                TextColumn::make('fromAreaTown.area_town')
                    ->searchable(),
                TextColumn::make('toAreaTown.area_town')
                    ->searchable(),
                TextColumn::make('transportMode.name')
                    ->searchable(),
                TextColumn::make('distance_km')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('distance_source')
                    ->searchable(),
                TextColumn::make('gps_distance_km')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_auto_generated')
                    ->boolean(),
            ])
            ->filters([
                TrashedFilter::make(),
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
