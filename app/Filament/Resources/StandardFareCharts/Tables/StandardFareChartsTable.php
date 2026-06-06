<?php

namespace App\Filament\Resources\StandardFareCharts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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

                TextInputColumn::make('distance_km')
                    ->label('Distance (KM)')
                    ->type('number')
                    ->step(0.01)
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable()
                    ->alignEnd(),

                TextInputColumn::make('fare_amount')
                    ->label('Fare (₹)')
                    ->type('number')
                    ->step(0.01)
                    ->rules(['required', 'numeric', 'min:0'])
                    ->sortable()
                    ->alignEnd(),

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

                SelectFilter::make('territory_id')
                    ->relationship('territory', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('patch_id')
                    ->relationship('patch', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('type_master_id')
                    ->relationship('typeMaster', 'name')
                    ->label('SFC Type')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),

                Filter::make('distance_range')
                    ->label('Distance Range')
                    ->schema([
                        TextInput::make('min_distance')
                            ->label('Min Distance')
                            ->numeric(),

                        TextInput::make('max_distance')
                            ->label('Max Distance')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['min_distance'] ?? null),
                                fn (Builder $query) => $query->where(
                                    'distance_km',
                                    '>=',
                                    $data['min_distance']
                                )
                            )
                            ->when(
                                filled($data['max_distance'] ?? null),
                                fn (Builder $query) => $query->where(
                                    'distance_km',
                                    '<=',
                                    $data['max_distance']
                                )
                            );
                    }),

                Filter::make('fare_range')
                    ->label('Fare Range')
                    ->schema([
                        TextInput::make('min_fare')
                            ->label('Min Fare')
                            ->numeric(),

                        TextInput::make('max_fare')
                            ->label('Max Fare')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['min_fare'] ?? null),
                                fn (Builder $query) => $query->where(
                                    'fare_amount',
                                    '>=',
                                    $data['min_fare']
                                )
                            )
                            ->when(
                                filled($data['max_fare'] ?? null),
                                fn (Builder $query) => $query->where(
                                    'fare_amount',
                                    '<=',
                                    $data['max_fare']
                                )
                            );
                    }),

                Filter::make('created_at')
                    ->label('Created Date')
                    ->schema([
                        DatePicker::make('from')
                            ->label('From'),

                        DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                filled($data['from'] ?? null),
                                fn (Builder $query) => $query->whereDate(
                                    'created_at',
                                    '>=',
                                    $data['from']
                                )
                            )
                            ->when(
                                filled($data['until'] ?? null),
                                fn (Builder $query) => $query->whereDate(
                                    'created_at',
                                    '<=',
                                    $data['until']
                                )
                            );
                    }),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(2)
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
