<?php

namespace App\Filament\Resources\SgipLimits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables;

class SgipLimitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applies_to')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('applies_to_id')
                    ->label('Target')
                    ->formatStateUsing(fn ($record) => match ($record->applies_to) {
                        'account'   => $record->account?->name,
                        'employee'  => $record->employee?->full_name,
                        'territory' => $record->territory?->name,
                        default     => 'Global',
                    }),

                Tables\Columns\TextColumn::make('item_type')
                    ->badge(),

                Tables\Columns\TextColumn::make('period')
                    ->badge(),

                Tables\Columns\TextColumn::make('max_quantity')
                    ->label('Max Qty'),

                Tables\Columns\TextColumn::make('max_value')
                    ->money('INR'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
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
