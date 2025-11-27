<?php

namespace App\Filament\Resources\ApprovalSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovalSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enabled_modules')
                    ->label('Enabled Modules')
                    ->formatStateUsing(fn ($state) => collect($state)->map(fn($m) => class_basename($m))->implode(', '))
                    ->badge()
                    ->listWithLineBreaks()
                    ->wrap(),
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
