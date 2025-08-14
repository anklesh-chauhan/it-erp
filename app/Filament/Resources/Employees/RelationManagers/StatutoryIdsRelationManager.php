<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StatutoryIdsRelationManager extends RelationManager
{
    protected static string $relationship = 'statutoryIds';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('pan')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('uan_no')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\DatePicker::make('group_join_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\TextInput::make('gratuity_code')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pran')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('aadhar_number')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('tax_code')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('tax_exemption')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\Textarea::make('tax_exemption_reason')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('tax_exemption_validity')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\Textarea::make('tax_exemption_remarks')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pan')
            ->columns([
                Tables\Columns\TextColumn::make('pan'),
                Tables\Columns\TextColumn::make('uan_no'),
                Tables\Columns\TextColumn::make('aadhar_number'),
                Tables\Columns\TextColumn::make('group_join_date')
                    ->date(),
                Tables\Columns\TextColumn::make('tax_exemption'),
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
