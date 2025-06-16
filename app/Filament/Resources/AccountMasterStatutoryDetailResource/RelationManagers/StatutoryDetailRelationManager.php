<?php

namespace App\Filament\Resources\AccountMasterStatutoryDetailResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatutoryDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'statutoryDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tan_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tds_parameters')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tds_section')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tds_rate')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tds_type')
                    ->maxLength(255),
                Forms\Components\Toggle::make('tds_status')
                    ->required()
                    ->default('active'),
                Forms\Components\TextInput::make('is_tds_deduct')
                    ->maxLength(255),
                Forms\Components\TextInput::make('is_tds_compulsory')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tds_remark')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('tan_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tds_parameters')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tds_section')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tds_rate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tds_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tds_status')
                    ->searchable(),
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
