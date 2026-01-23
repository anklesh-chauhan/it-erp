<?php

namespace App\Filament\Resources\CityPinCodes;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;
use App\Filament\Clusters\GlobalConfiguration\AddressConfigurationCluster;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CityPinCodes\Pages\ListCityPinCodes;
use App\Filament\Resources\CityPinCodes\Pages\CreateCityPinCode;
use App\Filament\Resources\CityPinCodes\Pages\EditCityPinCode;
use App\Filament\Resources\CityPinCodeResource\Pages;
use App\Filament\Resources\CityPinCodeResource\RelationManagers;
use App\Models\CityPinCode;
use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityPinCodeResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = CityPinCode::class;

    protected static ?string $cluster = AddressConfigurationCluster::class;
    protected static ?string $navigationLabel = 'Pin Codes';
    protected static ?int $navigationSort = 1;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, $state) =>
                        $set('state_id', City::find($state)?->state_id)
                    )
                    ->afterStateUpdated(fn (callable $set, $state) =>
                        $set('country_id', State::find($state)?->country_id)
                    ),
                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->disabled(),
                Select::make('country_id')
                    ->relationship('country', 'name')
                    ->disabled(),
                TextInput::make('pin_code')->required(),
                TextInput::make('area_town')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pin_code')->sortable()->searchable(),
                TextColumn::make('area_town')->sortable()->searchable(),
                TextColumn::make('city.name')->label('City')->searchable(),
                TextColumn::make('state.name')->label('State')->searchable(),
                TextColumn::make('country.name')->label('Country'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                        BulkApprovalAction::make(),

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
            'index' => ListCityPinCodes::route('/'),
            'create' => CreateCityPinCode::route('/create'),
            'edit' => EditCityPinCode::route('/{record}/edit'),
        ];
    }
}
