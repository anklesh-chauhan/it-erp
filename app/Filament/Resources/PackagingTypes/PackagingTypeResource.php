<?php

namespace App\Filament\Resources\PackagingTypes;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\PackagingTypes\Pages\ListPackagingTypes;
use App\Filament\Resources\PackagingTypes\Pages\CreatePackagingType;
use App\Filament\Resources\PackagingTypes\Pages\EditPackagingType;
use App\Filament\Resources\PackagingTypeResource\Pages;
use App\Filament\Resources\PackagingTypeResource\RelationManagers;
use App\Models\PackagingType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackagingTypeResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = PackagingType::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Items';
    protected static ?int $navigationSort = 1003;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
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
            'index' => ListPackagingTypes::route('/'),
            'create' => CreatePackagingType::route('/create'),
            'edit' => EditPackagingType::route('/{record}/edit'),
        ];
    }
}
