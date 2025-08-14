<?php

namespace App\Filament\Resources\AccountMasterStatutoryDetailResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatutoryDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'statutoryDetail';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tan_number')
                    ->maxLength(255),
                TextInput::make('cin')
                    ->maxLength(255),
                TextInput::make('tds_parameters')
                    ->maxLength(255),
                TextInput::make('tds_section')
                    ->maxLength(255),
                TextInput::make('tds_rate')
                    ->maxLength(255),
                TextInput::make('tds_type')
                    ->maxLength(255),
                Toggle::make('tds_status')
                    ->required()
                    ->default('active'),
                TextInput::make('is_tds_deduct')
                    ->maxLength(255),
                TextInput::make('is_tds_compulsory')
                    ->maxLength(255),
                TextInput::make('tds_remark')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('tan_number')
                    ->searchable(),
                TextColumn::make('cin')
                    ->searchable(),
                TextColumn::make('tds_parameters')
                    ->searchable(),
                TextColumn::make('tds_section')
                    ->searchable(),
                TextColumn::make('tds_rate')
                    ->searchable(),
                TextColumn::make('tds_type')
                    ->searchable(),
                TextColumn::make('tds_status')
                    ->searchable(),
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
