<?php

namespace App\Filament\Resources\WeekOffs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;

class WeekOffsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.first_name')
                    ->label('Employee')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('department.name')
                    ->label('Department')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('shift.name')
                    ->label('Shift')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('day_name')
                    ->label('Day')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('day_of_week')
            ->filters([
                SelectFilter::make('day_of_week')
                    ->options([
                        0 => 'Sunday',
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                    ]),
                TrashedFilter::make(),
            ])
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
