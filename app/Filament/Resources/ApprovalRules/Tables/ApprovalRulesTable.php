<?php

namespace App\Filament\Resources\ApprovalRules\Tables;

use App\Filament\Actions\ApprovalAction;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables;

class ApprovalRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('module', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('module')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('territory.name')
                    ->label('Territory')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Approver')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')->sortable(),

                Tables\Columns\TextColumn::make('min_amount')
                    ->money('INR')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_amount')
                    ->money('INR')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('module')
                    ->options(function () {
                        return \App\Models\ApprovalSetting::approvedModuleOptions();
                    }),

                Tables\Filters\SelectFilter::make('territory_id')
                    ->relationship('territory', 'name'),

                Tables\Filters\TernaryFilter::make('active'),
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
