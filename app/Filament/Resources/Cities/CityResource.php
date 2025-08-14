<?php

namespace App\Filament\Resources\Cities;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Filament\Resources\Cities\Pages\CreateCity;
use App\Filament\Resources\Cities\Pages\EditCity;
use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Address Config';
    protected static ?string $navigationLabel = 'City Master';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('country_id', null)),
                Select::make('country_id')
                    ->relationship('country', 'name')
                    ->required(),
                TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('state.name')->label('State'),
                TextColumn::make('country.name')->label('Country'),
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
            'index' => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'edit' => EditCity::route('/{record}/edit'),
        ];
    }
}
