<?php

namespace App\Filament\Resources\EmployeeAttendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions;

class EmployeeAttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()      // optional (remove if error)
                    ->searchable(),   // optional searchable
                Tables\Columns\TextColumn::make('attendance_date')->date('d M, Y')->sortable(),
                Tables\Columns\TextColumn::make('check_in'),
                Tables\Columns\TextColumn::make('check_out'),
                Tables\Columns\TextColumn::make('status.status')
                    ->badge()
                    ->color(fn ($record) => match ($record->status->status_code) {
                        'P' => 'success',
                        'A' => 'danger',
                        'L' => 'warning',
                        'HD' => 'info',
                        'LV' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_hours'),

                //add latitude and longitude
                Tables\Columns\TextColumn::make('check_in_latitude')
                    ->label('Check-in Latitude')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('check_in_longitude')
                    ->label('Check-in Longitude')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('check_out_latitude')
                    ->label('Check-out Latitude')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('check_out_longitude')
                    ->label('Check-out Longitude')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('attendance_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->relationship('status', 'status')
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
