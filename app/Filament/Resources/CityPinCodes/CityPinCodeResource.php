<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityPinCodeResource\Pages;
use App\Filament\Resources\CityPinCodeResource\RelationManagers;
use App\Models\CityPinCode;
use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityPinCodeResource extends Resource
{
    protected static ?string $model = CityPinCode::class;

    protected static ?string $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Address Config';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'name')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, $state) =>
                        $set('state_id', City::find($state)?->state_id)
                    )
                    ->afterStateUpdated(fn (callable $set, $state) =>
                        $set('country_id', State::find($state)?->country_id)
                    ),
                Forms\Components\Select::make('state_id')
                    ->relationship('state', 'name')
                    ->disabled(),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->disabled(),
                Forms\Components\TextInput::make('pin_code')->required(),
                Forms\Components\TextInput::make('area_town')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pin_code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('area_town')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('city.name')->label('City')->searchable(),
                Tables\Columns\TextColumn::make('state.name')->label('State')->searchable(),
                Tables\Columns\TextColumn::make('country.name')->label('Country'),
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
            'index' => Pages\ListCityPinCodes::route('/'),
            'create' => Pages\CreateCityPinCode::route('/create'),
            'edit' => Pages\EditCityPinCode::route('/{record}/edit'),
        ];
    }
}
