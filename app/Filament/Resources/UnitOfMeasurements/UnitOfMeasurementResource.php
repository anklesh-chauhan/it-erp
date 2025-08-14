<?php

namespace App\Filament\Resources\UnitOfMeasurements;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\UnitOfMeasurements\Pages\ListUnitOfMeasurements;
use App\Filament\Resources\UnitOfMeasurements\Pages\CreateUnitOfMeasurement;
use App\Filament\Resources\UnitOfMeasurements\Pages\EditUnitOfMeasurement;
use App\Filament\Resources\UnitOfMeasurementResource\Pages;
use App\Filament\Resources\UnitOfMeasurementResource\RelationManagers;
use App\Models\UnitOfMeasurement;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitOfMeasurementResource extends Resource
{
    protected static ?string $model = UnitOfMeasurement::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Items';
    protected static ?int $navigationSort = 1003;
    protected static ?string $navigationLabel = 'Unit of Measurement';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
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
            'index' => ListUnitOfMeasurements::route('/'),
            'create' => CreateUnitOfMeasurement::route('/create'),
            'edit' => EditUnitOfMeasurement::route('/{record}/edit'),
        ];
    }
}
