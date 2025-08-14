<?php

namespace App\Filament\Resources\Territories;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use App\Models\CityPinCode;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Territories\Pages\ListTerritories;
use App\Filament\Resources\Territories\Pages\CreateTerritory;
use App\Filament\Resources\Territories\Pages\EditTerritory;
use App\Enums\TerritoryStatus;
use App\Filament\Resources\TerritoryResource\Pages;
use App\Models\Territory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TerritoryResource extends Resource
{
    protected static ?string $model = Territory::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map';
    protected static string | \UnitEnum | null $navigationGroup = 'Marketing';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Territory Details')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('code')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->nullable(),

                    Select::make('status')
                        ->options(TerritoryStatus::class)
                        ->default(TerritoryStatus::Active)
                        ->required(),

                    Select::make('parent_territory_id')
                        ->relationship('parentTerritory', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a Parent Territory')
                        ->label('Parent Territory'),

                    Select::make('type_master_id')
                        ->relationship('typeMaster', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a Type Master')
                        ->label('Type Master'),

                    Textarea::make('description')
                        ->nullable()
                        ->columnSpanFull(),
                ]),
            
            Section::make('Associated Locations')
                ->schema([
                    Select::make('cityPinCodes')
                        ->multiple()
                        ->relationship('cityPinCodes', 'id')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => 
                            CityPinCode::query()
                                ->whereHas('city', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                                ->orWhere('area_town', 'like', "%{$search}%")
                                ->orWhere('pin_code', 'like', "%{$search}%")
                                ->with('city')
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->id => "{$item->city->name} - {$item->area_town} ({$item->pin_code})"
                                ])
                        )
                        ->getOptionLabelsUsing(fn ($values) => 
                            CityPinCode::whereIn('id', $values)
                                ->with('city')
                                ->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->id => "{$item->city->name} - {$item->area_town} ({$item->pin_code})"
                                ])
                        )
                        ->label('Cities and Areas')
                        ->required(),
                ]),
            
            Section::make('Associated Positions')
                ->schema([
                    Select::make('positions')
                        ->multiple()
                        ->relationship('positions', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Positions')
                        ->placeholder('Select positions linked to this territory'),
                ]),

            Section::make('Organizational Linkages')
                ->schema([
                    Select::make('organizationalUnits')
                        ->multiple()
                        ->relationship('organizationalUnits', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Select Organizational Units')
                        ->label('Organizational Units'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->searchable()->sortable(),

                TextColumn::make('parentTerritory.name')
                    ->label('Parent Territory')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('typeMaster.name')
                    ->label('Type Master')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => TerritoryStatus::from($state)->getColor())
                    ->searchable(),

                TextColumn::make('cityPinCodes')
                    ->label('Cities/Areas')
                    ->formatStateUsing(function (Model $record) {
                        $record->loadMissing('cityPinCodes.city');
                        return $record->cityPinCodes
                            ->map(fn ($pin) => "{$pin->city->name} - {$pin->area_town}")
                            ->implode(', ');
                    })
                    ->wrap()
                    ->toggleable()
                    ->searchable(query: fn (Builder $query, string $search) =>
                        $query->whereHas('cityPinCodes.city', fn ($q) =>
                            $q->where('name', 'like', "%{$search}%")
                        )->orWhereHas('cityPinCodes', fn ($q) =>
                            $q->where('area_town', 'like', "%{$search}%")
                              ->orWhere('pin_code', 'like', "%{$search}%")
                        )
                    ),

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
                SelectFilter::make('status')
                    ->options(TerritoryStatus::class)
                    ->placeholder('Filter by Status'),

                SelectFilter::make('type_master_id')
                    ->relationship('typeMaster', 'name')
                    ->label('Type Master')
                    ->placeholder('Filter by Type Master')
                    ->preload(),

                SelectFilter::make('parent_territory_id')
                    ->relationship('parentTerritory', 'name')
                    ->label('Parent Territory')
                    ->placeholder('Filter by Parent Territory')
                    ->preload(),

                SelectFilter::make('organizationalUnits')
                    ->multiple()
                    ->relationship('organizationalUnits', 'name')
                    ->label('Organizational Unit')
                    ->placeholder('Filter by Organizational Unit')
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            // Example: RelationManagers\OrganizationalUnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTerritories::route('/'),
            'create' => CreateTerritory::route('/create'),
            'edit'   => EditTerritory::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Code'   => $record->code,
            'Status' => $record->status,
        ];
    }
}
