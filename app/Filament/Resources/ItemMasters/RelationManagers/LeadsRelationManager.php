<?php

namespace App\Filament\Resources\ItemMasters\RelationManagers;

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

class LeadsRelationManager extends RelationManager
{
    protected static string $relationship = 'leads';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company.name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('quantity')
                    ->numeric()
                    ->nullable(),

                TextInput::make('price')
                    ->numeric()
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('company.name')
            ->columns([
                TextColumn::make('company.name'),
                TextColumn::make('quantity')->label('Quantity'),
                TextColumn::make('price')->label('Price'),
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
