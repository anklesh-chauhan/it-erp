<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProfessionalTaxRelationManager extends RelationManager
{
    protected static string $relationship = 'professionalTax';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('pt_flag')
                    ->default(false),
                Forms\Components\TextInput::make('pt_no')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_amount')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),
                Forms\Components\DatePicker::make('pt_join_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_state')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_city')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_zone')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_code')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_jv_code')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_jv_code_cr')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\TextInput::make('pt_jv_code_dr')
                    ->maxLength(50)
                    ->nullable(),
                Forms\Components\Textarea::make('pt_remarks')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\Textarea::make('pt_jv_code_remarks')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pt_no')
            ->columns([
                Tables\Columns\IconColumn::make('pt_flag')
                    ->boolean(),
                Tables\Columns\TextColumn::make('pt_no'),
                Tables\Columns\TextColumn::make('pt_amount')
                    ->money('INR'),
                Tables\Columns\TextColumn::make('pt_join_date')
                    ->date(),
                Tables\Columns\TextColumn::make('pt_state'),
                Tables\Columns\TextColumn::make('pt_city'),
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
