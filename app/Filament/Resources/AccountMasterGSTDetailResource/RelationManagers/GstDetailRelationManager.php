<?php

namespace App\Filament\Resources\AccountMasterGSTDetailResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GstDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'gstDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gst_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('state_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('state_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gst_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('gst_status')
                    ->required()
                    ->default('active'),
                Forms\Components\TextInput::make('pan_number')
                    ->maxLength(255),
                Forms\Components\Textarea::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('gst_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state_code')
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
