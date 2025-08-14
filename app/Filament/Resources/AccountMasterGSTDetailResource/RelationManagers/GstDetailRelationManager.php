<?php

namespace App\Filament\Resources\AccountMasterGSTDetailResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GstDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'gstDetail';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('gst_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('state_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('state_code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('gst_type')
                    ->required()
                    ->maxLength(255),
                Toggle::make('gst_status')
                    ->required()
                    ->default('active'),
                TextInput::make('pan_number')
                    ->maxLength(255),
                Textarea::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('gst_number')
                    ->searchable(),
                TextColumn::make('state_name')
                    ->searchable(),
                TextColumn::make('state_code')
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
