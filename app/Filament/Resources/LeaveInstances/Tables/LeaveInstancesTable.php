<?php

namespace App\Filament\Resources\LeaveInstances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LeaveInstancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.first_name')->label('Employee')->searchable(),
                TextColumn::make('leaveType.name')->label('Leave')->searchable(),
                TextColumn::make('date')->date(),
                TextColumn::make('pay_factor'),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'applied',
                        'danger' => 'rejected',
                    ]),
            ])
            ->filters([
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
