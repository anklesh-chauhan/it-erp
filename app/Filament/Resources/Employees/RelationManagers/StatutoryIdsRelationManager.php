<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
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

class StatutoryIdsRelationManager extends RelationManager
{
    protected static string $relationship = 'statutoryIds';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pan')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('uan_no')
                    ->maxLength(50)
                    ->nullable(),
                DatePicker::make('group_join_date')
                    ->native(false)
                    ->nullable(),
                TextInput::make('gratuity_code')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('pran')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('aadhar_number')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('tax_code')
                    ->maxLength(50)
                    ->nullable(),
                TextInput::make('tax_exemption')
                    ->maxLength(50)
                    ->nullable(),
                Textarea::make('tax_exemption_reason')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('tax_exemption_validity')
                    ->maxLength(50)
                    ->nullable(),
                Textarea::make('tax_exemption_remarks')
                    ->maxLength(255)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pan')
            ->columns([
                TextColumn::make('pan'),
                TextColumn::make('uan_no'),
                TextColumn::make('aadhar_number'),
                TextColumn::make('group_join_date')
                    ->date(),
                TextColumn::make('tax_exemption'),
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
