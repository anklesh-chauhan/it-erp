<?php

namespace App\Filament\Resources\EmployeeAttendanceStatuses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeAttendanceStatusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\ColorColumn::make('color_code')
                    ->label('Color')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->tooltip(fn ($record) => $record->remarks)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('remarks')->limit(50),
            ])
            ->defaultSort('status_code')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
