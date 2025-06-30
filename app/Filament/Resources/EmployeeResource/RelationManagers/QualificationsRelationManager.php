<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QualificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'qualifications';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('degree')
                    ->maxLength(100)
                    ->nullable(),
                Forms\Components\TextInput::make('institution')
                    ->maxLength(100)
                    ->nullable(),
                Forms\Components\TextInput::make('year_of_completion')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y'))
                    ->nullable(),
                Forms\Components\TextInput::make('certification')
                    ->maxLength(100)
                    ->nullable(),
                Forms\Components\TextInput::make('grade')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('percentage')
                    ->maxLength(10)
                    ->nullable(),
                Forms\Components\Textarea::make('remarks')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('degree')
            ->columns([
                Tables\Columns\TextColumn::make('degree'),
                Tables\Columns\TextColumn::make('institution'),
                Tables\Columns\TextColumn::make('year_of_completion'),
                Tables\Columns\TextColumn::make('certification'),
                Tables\Columns\TextColumn::make('grade'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
