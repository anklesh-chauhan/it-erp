<?php

namespace App\Traits;

use App\Models\Address;
use App\Models\CityPinCode;
use App\Models\TypeMaster;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;

trait CreateAddressFormTrait
{
    public static function getCreateAddressFormFields(): array
    {
        return [
            Select::make('type_master_id')
                ->label('Address Type')
                ->options(
                    TypeMaster::query()
                        ->where('typeable_type', Address::class) // Filter for Address types
                        ->pluck('name', 'id')
                )
                ->required()
                ->searchable(),
            TextInput::make('street')
                ->required(),
            TextInput::make('pin_code')
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state) {
                    $pinCodeDetails = CityPinCode::where('pin_code', $state)->first();
                    if ($pinCodeDetails) {
                        $set('area_town_id', $pinCodeDetails->area_town);
                        $set('city_id', $pinCodeDetails->city_id);
                        $set('state_id', $pinCodeDetails->state_id);
                        $set('country_id', $pinCodeDetails->country_id);
                    } else {
                        // Reset if pin is invalid or has multiple areas
                        $set('area_town_id', null);
                    }
                }),

            Select::make('area_town_id')
                ->label('Area/Town')
                ->relationship(
                    name: 'areaTown',
                    titleAttribute: 'area_town',
                    modifyQueryUsing: fn (Builder $query, callable $get) => $query->where('pin_code', $get('pin_code'))
                )
                ->required()
                ->live(),

            Select::make('city_id')
                ->relationship('city', 'name')
                ->searchable(),
            Select::make('state_id')
                ->relationship('state', 'name')
                ->searchable(),
            Select::make('country_id')
                ->relationship('country', 'name')
                ->searchable(),

            Toggle::make('is_primary'),
        ];
    }
}
