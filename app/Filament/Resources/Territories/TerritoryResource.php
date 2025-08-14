<?php

namespace App\Filament\Resources;

use App\Enums\TerritoryStatus;
use App\Filament\Resources\TerritoryResource\Pages;
use App\Models\Territory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TerritoryResource extends Resource
{
    protected static ?string $model = Territory::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Territory Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('code')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->nullable(),

                    Forms\Components\Select::make('status')
                        ->options(TerritoryStatus::class)
                        ->default(TerritoryStatus::Active)
                        ->required(),

                    Forms\Components\Select::make('parent_territory_id')
                        ->relationship('parentTerritory', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a Parent Territory')
                        ->label('Parent Territory'),

                    Forms\Components\Select::make('type_master_id')
                        ->relationship('typeMaster', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a Type Master')
                        ->label('Type Master'),

                    Forms\Components\Textarea::make('description')
                        ->nullable()
                        ->columnSpanFull(),
                ]),
            
            Forms\Components\Section::make('Associated Locations')
                ->schema([
                    Forms\Components\Select::make('cityPinCodes')
                        ->multiple()
                        ->relationship('cityPinCodes', 'id')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => 
                            \App\Models\CityPinCode::query()
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
                            \App\Models\CityPinCode::whereIn('id', $values)
                                ->with('city')
                                ->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->id => "{$item->city->name} - {$item->area_town} ({$item->pin_code})"
                                ])
                        )
                        ->label('Cities and Areas')
                        ->required(),
                ]),
            
            Forms\Components\Section::make('Associated Positions')
                ->schema([
                    Forms\Components\Select::make('positions')
                        ->multiple()
                        ->relationship('positions', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Positions')
                        ->placeholder('Select positions linked to this territory'),
                ]),

            Forms\Components\Section::make('Organizational Linkages')
                ->schema([
                    Forms\Components\Select::make('organizationalUnits')
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
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('parentTerritory.name')
                    ->label('Parent Territory')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('typeMaster.name')
                    ->label('Type Master')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => TerritoryStatus::from($state)->getColor())
                    ->searchable(),

                Tables\Columns\TextColumn::make('cityPinCodes')
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
                    ->placeholder('Filter by Status'),

                Tables\Filters\SelectFilter::make('type_master_id')
                    ->relationship('typeMaster', 'name')
                    ->label('Type Master')
                    ->placeholder('Filter by Type Master')
                    ->preload(),

                Tables\Filters\SelectFilter::make('parent_territory_id')
                    ->relationship('parentTerritory', 'name')
                    ->label('Parent Territory')
                    ->placeholder('Filter by Parent Territory')
                    ->preload(),

                Tables\Filters\SelectFilter::make('organizationalUnits')
                    ->multiple()
                    ->relationship('organizationalUnits', 'name')
                    ->label('Organizational Unit')
                    ->placeholder('Filter by Organizational Unit')
                    ->preload(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListTerritories::route('/'),
            'create' => Pages\CreateTerritory::route('/create'),
            'edit'   => Pages\EditTerritory::route('/{record}/edit'),
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
