<?php

namespace App\Filament\Resources\SalesDcrExpenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables;
use Filament\Tables\Table;

class SalesDcrExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dcr.dcr_date')
                    ->label('DCR Date')
                    ->date(),

                Tables\Columns\TextColumn::make('dcr.user.name')
                    ->label('User'),

                Tables\Columns\TextColumn::make('expenseType.name')
                    ->label('Expense'),

                Tables\Columns\TextColumn::make('transportMode.name')
                    ->label('Transport')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_auto_calculated')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('expense_type_id')
                    ->relationship('expenseType', 'name'),

                Tables\Filters\SelectFilter::make('mode_of_transport_id')
                    ->relationship('transportMode', 'name'),
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
