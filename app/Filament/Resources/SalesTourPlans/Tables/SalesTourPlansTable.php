<?php

namespace App\Filament\Resources\SalesTourPlans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables;

class SalesTourPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Sales Employee')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('month')->label('Month')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])->sortable()->searchable()->label('Status')->badge(),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Approved By'),
                Tables\Columns\TextColumn::make('approved_at')->dateTime()->label('Approved At'),
            ])
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
