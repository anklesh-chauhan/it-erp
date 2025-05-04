<?php

namespace App\Traits;

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
                Forms\Components\Select::make('type_master_id')
                    ->label('Address Type')
                    ->options(
                        \App\Models\TypeMaster::query()
                            ->where('typeable_type', \App\Models\Address::class) // Filter for Address types
                            ->pluck('name', 'id')
                    )
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('street')
                    ->required(),
                Forms\Components\TextInput::make('area_town')
                    ->required(),
                Forms\Components\TextInput::make('pin_code')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $pinCodeDetails = \App\Models\CityPinCode::where('pin_code', $state)->first();
                        if ($pinCodeDetails) {
                            $set('area_town', $pinCodeDetails->area_town);
                            $set('city_id', $pinCodeDetails->city_id);
                            $set('state_id', $pinCodeDetails->state_id);
                            $set('country_id', $pinCodeDetails->country_id);
                        }
                    }),
                Forms\Components\Select::make('city_id')
                    ->relationship('city', 'name')
                    ->searchable(),
                Forms\Components\Select::make('state_id')
                    ->relationship('state', 'name')
                    ->searchable(),
                Forms\Components\Select::make('country_id')
                    ->relationship('country', 'name')
                    ->searchable(),
        ];
    }
}
