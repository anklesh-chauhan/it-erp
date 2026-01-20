<?php

namespace App\Filament\Resources\LeaveApplications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class LeaveApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.employee.fullName')
                    ->label('Employee'),

                TextColumn::make('leaveType.name')->label('Leave'),

                TextColumn::make('from_date')->date(),
                TextColumn::make('to_date')->date(),

                TextColumn::make('approval_status')
                    ->badge()
                    ->colors([
                        'primary' => 'applied',
                        'warning' => 'draft',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),

                TextColumn::make('applied_at')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
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
