<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemMeasurementUnitResource\Pages;
use App\Filament\Resources\ItemMeasurementUnitResource\RelationManagers;
use App\Models\ItemMeasurementUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemMeasurementUnitResource extends Resource
{
    protected static ?string $model = ItemMeasurementUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Items';
    protected static ?int $navigationSort = 1003;
    protected static ?string $navigationLabel = 'Item Measurement Unit';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('item_master_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unit_of_measurement_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('conversion_rate')
                    ->required()
                    ->numeric()
                    ->default(1.00),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_master_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_of_measurement_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion_rate')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListItemMeasurementUnits::route('/'),
            'create' => Pages\CreateItemMeasurementUnit::route('/create'),
            'edit' => Pages\EditItemMeasurementUnit::route('/{record}/edit'),
        ];
    }
}
