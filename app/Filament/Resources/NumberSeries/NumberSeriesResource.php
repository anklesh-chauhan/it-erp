<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NumberSeriesResource\Pages;
use App\Filament\Resources\NumberSeriesResource\RelationManagers;
use App\Models\NumberSeries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Helpers\ModelHelper;

class NumberSeriesResource extends Resource
{
    protected static ?string $model = NumberSeries::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 1000;
    protected static ?string $navigationLabel = 'Number Series';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('model_type')
                    ->label('Number Series Type')
                    ->options(ModelHelper::getModelOptions()) // Dynamic Model Names
                    ->required(),
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('Prefix')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('next_number')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('Suffix')
                            ->maxLength(255),
                    ]),
                Forms\Components\Select::make('type_master_id')
                    ->label('Module Type')
                    ->helperText('Select the module type for this number series.')
                    ->options(
                        \App\Models\TypeMaster::query()
                            ->where('typeable_type', \App\Models\AccountMaster::class) // Filter for Address types
                            ->pluck('name', 'id')
                    )
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Module')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => class_basename($state)),
                Tables\Columns\TextInputColumn::make('Prefix')
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('next_number')
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('Suffix')
                    ->searchable(),
                Tables\Columns\TextColumn::make('typeMaster.name') // Updated to match relationship name
                    ->label('Module Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNumberSeries::route('/'),
            'create' => Pages\CreateNumberSeries::route('/create'),
            'edit' => Pages\EditNumberSeries::route('/{record}/edit'),
        ];
    }
}
