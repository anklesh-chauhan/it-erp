<?php

namespace App\Filament\Resources\NumberSeries;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use App\Models\TypeMaster;
use App\Models\AccountMaster;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\NumberSeries\Pages\ListNumberSeries;
use App\Filament\Resources\NumberSeries\Pages\CreateNumberSeries;
use App\Filament\Resources\NumberSeries\Pages\EditNumberSeries;
use App\Filament\Resources\NumberSeriesResource\Pages;
use App\Filament\Resources\NumberSeriesResource\RelationManagers;
use App\Models\NumberSeries;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Helpers\ModelHelper;

class NumberSeriesResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = NumberSeries::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 1000;
    protected static ?string $navigationLabel = 'Number Series';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('model_type')
                    ->label('Number Series Type')
                    ->options(ModelHelper::getModelOptions()) // Dynamic Model Names
                    ->preload()
                    ->searchable()
                    ->required(),
                Grid::make(3)
                    ->schema([
                        TextInput::make('Prefix')
                            ->maxLength(255),
                        TextInput::make('next_number')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('Suffix')
                            ->maxLength(255),
                    ]),
                Select::make('type_master_id')
                    ->label('Module Type')
                    ->helperText('Select the module type for this number series.')
                    ->options(
                        TypeMaster::query()
                            ->where('typeable_type', AccountMaster::class) // Filter for Address types
                            ->pluck('name', 'id')
                    )
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('model_type')
                    ->label('Module')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => class_basename($state)),
                TextInputColumn::make('Prefix')
                    ->searchable(),
                TextInputColumn::make('next_number')
                    ->sortable(),
                TextInputColumn::make('Suffix')
                    ->searchable(),
                TextColumn::make('typeMaster.name') // Updated to match relationship name
                    ->label('Module Type')
                    ->sortable()
                    ->searchable(),
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
                //
            ])
            ->recordActions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNumberSeries::route('/'),
            'create' => CreateNumberSeries::route('/create'),
            'edit' => EditNumberSeries::route('/{record}/edit'),
        ];
    }
}
