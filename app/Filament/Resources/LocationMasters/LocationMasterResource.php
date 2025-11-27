<?php

namespace App\Filament\Resources\LocationMasters;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\LocationMasters\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\LocationMasters\Pages\ListLocationMasters;
use App\Filament\Resources\LocationMasters\Pages\CreateLocationMaster;
use App\Filament\Resources\LocationMasters\Pages\EditLocationMaster;
use App\Filament\Resources\LocationMasters\Pages\ViewLocationMaster;
use App\Filament\Resources\LocationMasterResource\Pages;
use App\Filament\Resources\LocationMasterResource\RelationManagers;
use App\Models\LocationMaster;
use App\Models\NumberSeries;
use App\Models\TypeMaster;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class LocationMasterResource extends Resource
{
    protected static ?string $model = LocationMaster::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Masters';
    protected static ?int $navigationSort = 200;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Location Name')->required(),
                TextInput::make('location_code')
                    ->label('Location Code')
                    ->default(fn () => NumberSeries::getNextNumber(LocationMaster::class))
                    ->disabled()
                    ->dehydrated(true),

                Select::make('parent_id')
                    ->label('Parent Location')
                    ->options(LocationMaster::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Textarea::make('description')->label('Description'),

                Select::make('typeable_id')
                    ->label('Type')
                    ->options(fn () => TypeMaster::where('typeable_type', LocationMaster::class)->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('addressable_id')
                    ->label('Address')
                    ->relationship('address', 'street')
                    ->searchable(),

                Select::make('contactable_id')
                    ->label('Contact Detail')
                    ->relationship('contactDetail', 'full_name')
                    ->searchable(),

                TextInput::make('latitude')
                    ->label('Latitude')
                    ->numeric()
                    ->mask('99.9999999'),

                TextInput::make('longitude')
                    ->label('Longitude')
                    ->numeric()
                    ->mask('99.9999999'),

                FileUpload::make('image')->label('Image'),

                Toggle::make('is_active')->label('Active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Location Name')->sortable()->searchable(),
                TextColumn::make('location_code')->label('Code')->sortable()->searchable(),
                // Show Type Name from Morph Relation
                TextColumn::make('typeable.name')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('parentLocation.name')
                    ->label('Parent Location')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('latitude')->label('Latitude')->sortable(),
                TextColumn::make('longitude')->label('Longitude')->sortable(),
                ImageColumn::make('image')->label('Image'),
                TextColumn::make('is_active')->label('Active')->badge(),
            ])
            ->filters([
                Filter::make('is_active')->label('Active Locations')
                    ->query(fn ($query) => $query->where('is_active', true)),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ApprovalAction::make(),
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
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocationMasters::route('/'),
            'create' => CreateLocationMaster::route('/create'),
            'edit' => EditLocationMaster::route('/{record}/edit'),
            'view' => ViewLocationMaster::route('/{record}'),
        ];
    }
}
