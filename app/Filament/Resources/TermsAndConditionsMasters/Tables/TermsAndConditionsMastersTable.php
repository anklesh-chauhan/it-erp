<?php

namespace App\Filament\Resources\TermsAndConditionsMasters\Tables;

use App\Filament\Actions\ApprovalAction;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class TermsAndConditionsMastersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document_type')->sortable()->searchable(),
                TextColumn::make('title')->sortable()->searchable(),
                IconColumn::make('is_default')->boolean(),
                TextColumn::make('created_at')->date(),
            ])
            ->filters([
                //
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
