<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
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

class ProfessionalTaxRelationManager extends RelationManager
{
    protected static string $relationship = 'professionalTax';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('pt_flag')
                    ->default(false),
                TextInput::make('pt_no')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pt_amount')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),
                DatePicker::make('pt_join_date')
                    ->native(false)
                    ->nullable(),
                TextInput::make('pt_state')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pt_city')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pt_zone')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pt_code')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pt_jv_code')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pt_jv_code_cr')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pt_jv_code_dr')
                    ->maxLength(50)
                    ->nullable(),
                Textarea::make('pt_remarks')
                    ->maxLength(255)
                    ->nullable(),
                Textarea::make('pt_jv_code_remarks')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pt_no')
            ->columns([
                IconColumn::make('pt_flag')
                    ->boolean(),
                TextColumn::make('pt_no'),
                TextColumn::make('pt_amount')
                    ->money('INR'),
                TextColumn::make('pt_join_date')
                    ->date(),
                TextColumn::make('pt_state'),
                TextColumn::make('pt_city'),
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
