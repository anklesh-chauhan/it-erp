<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use App\Models\CityPinCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationParentItem = 'Contacts';
    protected static ?string $navigationLabel = 'Companies';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 60;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('secondary_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_of_employees')
                    ->maxLength(255),
                Forms\Components\TextInput::make('twitter')
                    ->maxLength(255),
                Forms\Components\TextInput::make('linked_in')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('industry_type_id')
                    ->relationship('industryType', 'name')
                    ->searchable()
                    ->nullable()
                    ->label('Industry Type')
                    ->preload(), // Preload data for faster search

                // 🔄 Add Address Repeater
                Forms\Components\Repeater::make('addresses')
                ->relationship('addresses')
                ->schema([
                    Forms\Components\Hidden::make('address_type')->default('Company'),

                    Forms\Components\TextInput::make('street')
                        ->required(),

                    // 🔍 Pin Code (Auto-fills fields only when changed)
                    Forms\Components\TextInput::make('pin_code')
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                            if (!$get('city_id')) { // Only auto-fill if city is NOT set
                                $pinCodeDetails = CityPinCode::where('pin_code', $state)->first();

                                if ($pinCodeDetails) {
                                    $set('area_town', $pinCodeDetails->area_town);
                                    $set('city_id', $pinCodeDetails->city_id);
                                    $set('state_id', $pinCodeDetails->state_id);
                                    $set('country_id', $pinCodeDetails->country_id);
                                }
                            }
                        }),

                    // 🔍 City (Auto-fills fields only when changed)
                    Forms\Components\Select::make('city_id')
                        ->relationship('city', 'name')
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                            if (!$get('pin_code')) { // Only auto-fill if pin_code is NOT set
                                $pinCodeDetails = CityPinCode::where('city_id', $state)->first();

                                if ($pinCodeDetails) {
                                    $set('area_town', $pinCodeDetails->area_town);
                                    $set('pin_code', $pinCodeDetails->pin_code);
                                    $set('state_id', $pinCodeDetails->state_id);
                                    $set('country_id', $pinCodeDetails->country_id);
                                }
                            }
                        }),

                    // 🔍 Area/Town (Save as a string only)
                    Forms\Components\TextInput::make('area_town')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                            // Area/Town change will NOT affect pin_code, city, etc.
                            $set('area_town', $state); // Save the entered value directly
                        }),

                    Forms\Components\Select::make('state_id')
                        ->relationship('state', 'name')->searchable(),

                    Forms\Components\Select::make('country_id')
                        ->relationship('country', 'name')->searchable(),
                ])
                ->collapsible() // Optional for better UI
                ->orderColumn() // Enables drag & drop sorting
                ->addActionLabel('Add Address') // Custom add button text
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('secondary_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_of_employees')
                    ->searchable(),
                Tables\Columns\TextColumn::make('twitter')
                    ->searchable(),
                Tables\Columns\TextColumn::make('linked_in')
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
