<?php

namespace App\Filament\Resources\AddressTypes;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;
use App\Filament\Clusters\GlobalConfiguration\AddressConfigurationCluster;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\AddressTypes\Pages\ListAddressTypes;
use App\Filament\Resources\AddressTypes\Pages\CreateAddressType;
use App\Filament\Resources\AddressTypes\Pages\EditAddressType;
use App\Filament\Resources\AddressTypeResource\Pages;
use App\Filament\Resources\AddressTypeResource\RelationManagers;
use App\Models\AddressType;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressTypeResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = AddressType::class;

    protected static ?string $cluster = AddressConfigurationCluster::class;
    protected static ?string $navigationParentItem = 'Address Config';
    protected static ?int $navigationSort = 2;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
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

                        BulkApprovalAction::make(),

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
            'index' => ListAddressTypes::route('/'),
            'create' => CreateAddressType::route('/create'),
            'edit' => EditAddressType::route('/{record}/edit'),
        ];
    }
}
