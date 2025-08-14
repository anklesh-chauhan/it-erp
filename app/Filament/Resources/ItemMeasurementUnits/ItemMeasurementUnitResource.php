<?php

namespace App\Filament\Resources\ItemMeasurementUnits;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ItemMeasurementUnits\Pages\ListItemMeasurementUnits;
use App\Filament\Resources\ItemMeasurementUnits\Pages\CreateItemMeasurementUnit;
use App\Filament\Resources\ItemMeasurementUnits\Pages\EditItemMeasurementUnit;
use App\Filament\Resources\ItemMeasurementUnitResource\Pages;
use App\Filament\Resources\ItemMeasurementUnitResource\RelationManagers;
use App\Models\ItemMeasurementUnit;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemMeasurementUnitResource extends Resource
{
    protected static ?string $model = ItemMeasurementUnit::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Items';
    protected static ?int $navigationSort = 1003;
    protected static ?string $navigationLabel = 'Item Measurement Unit';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_master_id')
                    ->required()
                    ->numeric(),
                TextInput::make('unit_of_measurement_id')
                    ->required()
                    ->numeric(),
                TextInput::make('conversion_rate')
                    ->required()
                    ->numeric()
                    ->default(1.00),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_master_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_of_measurement_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('conversion_rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListItemMeasurementUnits::route('/'),
            'create' => CreateItemMeasurementUnit::route('/create'),
            'edit' => EditItemMeasurementUnit::route('/{record}/edit'),
        ];
    }
}
