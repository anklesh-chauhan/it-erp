<?php

namespace App\Filament\Resources\LocationMasters\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('quantity') // Pivot field
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_name')
            ->columns([
                TextColumn::make('item_name'),
                TextColumn::make('pivot.quantity') // Display pivot field
                    ->label('Quantity'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
