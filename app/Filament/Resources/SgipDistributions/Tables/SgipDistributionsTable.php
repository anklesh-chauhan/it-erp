<?php

namespace App\Filament\Resources\SgipDistributions\Tables;

use App\Services\SGIPComplianceService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SgipDistributionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user')
                    ->label('Sales Employee')
                    ->getStateUsing(fn ($record) => $record->user?->employee?->full_name
                    )
                    ->visible(fn () => ! Auth::user()->hasRole('sales_user')),

                Tables\Columns\TextColumn::make('visit_date')
                    ->date(),

                Tables\Columns\TextColumn::make('total_value')
                    ->money('INR'),

                Tables\Columns\TextColumn::make('approval_status')
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'info' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record) => $record->approval_status === 'draft'),

                Action::make('submit')
                    ->label('Submit')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->approval_status === 'draft')
                    ->action(function ($record) {
                        SGIPComplianceService::validate($record, true);

                        $record->update(['approval_status' => 'submitted']);
                    }),
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
