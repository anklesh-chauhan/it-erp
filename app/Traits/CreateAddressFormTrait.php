<?php

namespace App\Traits;

use Filament\Forms\Components\Select;
use App\Models\TypeMaster;
use App\Models\Address;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\ContactDetail;
use App\Models\CityPinCode;
use App\Models\Company;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

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
                            $set('area_town', $pinCodeDetails->area_town);
                            $set('city_id', $pinCodeDetails->city_id);
                            $set('state_id', $pinCodeDetails->state_id);
                            $set('country_id', $pinCodeDetails->country_id);
                        }
                    }),
                TextInput::make('area_town')
                    ->required(),

                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->searchable(),
                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->searchable(),
                Select::make('country_id')
                    ->relationship('country', 'name')
                    ->searchable(),
        ];
    }
}
