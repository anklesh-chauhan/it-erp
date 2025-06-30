<?php

namespace App\Filament\Resources;

use App\Enums\TerritoryStatus;
use App\Filament\Resources\TerritoryResource\Pages;
use App\Filament\Resources\TerritoryResource\RelationManagers;
use App\Models\OrganizationalUnit; // Added for relationships
use App\Models\Position; // Added for relationships
use App\Models\Territory;
use App\Models\TypeMaster; // Added for relationships
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log; // Import Log for debugging
use App\Models\CityPinCode; // Import the CityPinCode model for the lookup functionality
use Filament\Tables\Filters\TextFilter;

class TerritoryResource extends Resource
{
    protected static ?string $model = Territory::class;

    protected static ?string $navigationIcon = 'heroicon-o-map'; // You can choose a different icon

    protected static ?string $navigationGroup = 'HR & Organization'; // Optional: Group navigation items

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Territory Details')
                    ->schema([
                        Forms\Components\Select::make('city_pin_code_id_for_lookup')
                            ->label('Search Area/Town or Pin Code')
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                if (strlen($search) < 2) {
                                    return []; // Don't search until at least 2 characters
                                }

                                return CityPinCode::query()
                                    ->where('area_town', 'like', "%{$search}%")
                                    ->orWhere('pin_code', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->get()
                                    ->mapWithKeys(fn ($record) => [
                                        $record->id => "{$record->area_town} ({$record->pin_code})",
                                    ])
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => CityPinCode::find($value)?->area_town)
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Forms\Set $set) {
                                if ($state) {
                                    $cityPinCode = CityPinCode::with(['city', 'state', 'country'])->find($state);
                                    if ($cityPinCode) {
                                        $set('name', $cityPinCode->area_town);
                                        $set('postal_code', $cityPinCode->pin_code);
                                        $set('city', $cityPinCode->city?->name);
                                        $set('state', $cityPinCode->state?->name);
                                        $set('country', $cityPinCode->country?->name);
                                    } else {
                                        Log::warning('CityPinCode not found for ID: ' . $state);
                                    }
                                } else {
                                    $set('name', null);
                                    $set('postal_code', null);
                                    $set('city', null);
                                    $set('state', null);
                                    $set('country', null);
                                }
                            })
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('code')
                            ->unique(ignoreRecord: true)
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Select::make('status')
                            ->options(TerritoryStatus::class) // Using the enum for options
                            ->default(TerritoryStatus::Active)
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('postal_code')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('city')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('state')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('country')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Relationships')
                    ->schema([
                        Forms\Components\Select::make('parent_territory_id')
                            ->label('Parent Territory')
                            ->relationship('parent', 'name') // Assuming 'name' is the display field
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a parent territory'),

                        Forms\Components\Select::make('type_master_id')
                            ->label('Territory Type')
                            ->options(
                                \App\Models\TypeMaster::query()
                                    ->ofType(\App\Models\Territory::class) // Filter by the `Address` model
                                    ->pluck('name', 'id') // Get the name and ID for the dropdown
                            )
                            ->searchable(),
                        Forms\Components\Select::make('reporting_position_id')
                            ->label('Reporting Position')
                            ->relationship('reportingPosition', 'name') // Assuming 'name' is the display field
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a reporting position'),
                        // Relationship for Organizational Units (BelongsToMany)
                        Forms\Components\Select::make('organizationalUnits')
                            ->multiple()
                            ->relationship('organizationalUnits', 'name') // Assuming 'name' is the display field
                            ->searchable()
                            ->preload()
                            ->label('Associated Organizational Units'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('postal_code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Territory')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('typeMaster.name')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reportingPosition.name')
                    ->label('Reporting Position')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                // New: Column to display associated Organizational Units
                Tables\Columns\TextColumn::make('organizationalUnits.name')
                    ->label('Organizational Units')
                    ->listWithLineBreaks() // Displays each associated unit on a new line
                    ->bulleted() // Adds a bullet point for each unit
                    ->searchable() // Allows searching through associated unit names
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // Custom sort for many-to-many relationship
                        // This will sort by the name of the first associated organizational unit
                        return $query->orderBy(
                            OrganizationalUnit::select('name')
                                ->whereColumn('territory_organizational_unit_pivot.organizational_unit_id', 'organizational_units.id')
                                ->whereColumn('territory_organizational_unit_pivot.territory_id', 'territories.id')
                                ->limit(1),
                            $direction
                        );
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
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
                Tables\Filters\SelectFilter::make('status')
                    ->options(TerritoryStatus::class)
                    ->label('Status'),
                Tables\Filters\SelectFilter::make('type_master_id')
                    ->label('Type')
                    ->relationship('typeMaster', 'name'),
                Tables\Filters\SelectFilter::make('parent_territory_id')
                    ->label('Parent Territory')
                    ->relationship('parent', 'name'),
                Tables\Filters\SelectFilter::make('organizationalUnits')
                    ->relationship('organizationalUnits', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Organizational Units'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // You can add Relation Managers here if you need to manage related data directly from the Territory form/page.
            // For example:
            // RelationManagers\OrganizationalUnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTerritories::route('/'),
            'create' => Pages\CreateTerritory::route('/create'),
            'edit' => Pages\EditTerritory::route('/{record}/edit'),
        ];
    }
}
