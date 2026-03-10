<?php

namespace App\Filament\Resources\ExpenseConfigurations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ExpenseConfigurationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expenseType.name')->label('Expense'),
                TextColumn::make('calculation_type'),
                TextColumn::make('rate'),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('jobRole.name')->label('Job Role'),
                TextColumn::make('position.name')->label('Position'),
                TextColumn::make('territory.name')->label('Territory'),
                TextColumn::make('transportMode.name')->label('Transport Mode'),
                TextColumn::make('effective_from')->date('d-m-Y'),
                TextColumn::make('effective_to')->date('d-m-Y'),

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
