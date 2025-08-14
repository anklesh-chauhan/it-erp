<?php

namespace App\Filament\Resources\EmpDepartments\RelationManagers;

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

class GradesRelationManager extends RelationManager
{
    protected static string $relationship = 'grades';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('grade_name')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('grade_name')
            ->columns([
                TextColumn::make('grade_name'),
                TextColumn::make('description'),
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
