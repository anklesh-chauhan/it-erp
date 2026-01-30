<?php

namespace App\Filament\Resources\Visits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables;
use App\Models\Visit;
use Filament\Actions\Action;

class VisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')->searchable(),
                Tables\Columns\TextColumn::make('visit_date')->date(),
                Tables\Columns\TextColumn::make('employee.name')->label('Employee'),
                Tables\Columns\TextColumn::make('patch.name'),
                Tables\Columns\TextColumn::make('visit_status')->badge(),
                Tables\Columns\TextColumn::make('approval_status')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('complete')
                    ->label('Complete')
                    ->visible(fn (Visit $record) => $record->visit_status !== 'completed')
                    ->action(fn (Visit $record) =>
                        $record->update(['visit_status' => 'completed'])
                    ),

                Action::make('send_for_approval')
                    ->visible(fn (Visit $record) => $record->visit_status === 'completed')
                    ->action(fn (Visit $record) =>
                        $record->update(['approval_status' => 'pending'])
                    ),
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
