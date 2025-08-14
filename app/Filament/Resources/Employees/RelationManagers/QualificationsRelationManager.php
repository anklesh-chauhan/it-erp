<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QualificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'qualifications';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('degree')
                    ->maxLength(100)
                    ->nullable(),
                TextInput::make('institution')
                    ->maxLength(100)
                    ->nullable(),
                TextInput::make('year_of_completion')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y'))
                    ->nullable(),
                TextInput::make('certification')
                    ->maxLength(100)
                    ->nullable(),
                TextInput::make('grade')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('percentage')
                    ->maxLength(10)
                    ->nullable(),
                Textarea::make('remarks')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('degree')
            ->columns([
                TextColumn::make('degree'),
                TextColumn::make('institution'),
                TextColumn::make('year_of_completion'),
                TextColumn::make('certification'),
                TextColumn::make('grade'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
