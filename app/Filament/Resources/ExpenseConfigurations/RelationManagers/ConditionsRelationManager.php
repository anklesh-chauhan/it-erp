<?php

namespace App\Filament\Resources\ExpenseConfigurations\RelationManagers;

use App\Models\ExpenseConfigurationCondition;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;

class ConditionsRelationManager extends RelationManager
{
    protected static string $relationship = 'conditions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('condition_key')
                ->options(ExpenseConfigurationCondition::CONDITION_KEYS)
                ->required(),

                Forms\Components\Select::make('operator')
                    ->options(ExpenseConfigurationCondition::OPERATORS)
                    ->required(),

                Forms\Components\TextInput::make('value')
                    ->required(),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('conditions')
            ->columns([
                Tables\Columns\TextColumn::make('condition_key')->badge(),
                Tables\Columns\TextColumn::make('operator')->badge(),
                Tables\Columns\TextColumn::make('value')->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
