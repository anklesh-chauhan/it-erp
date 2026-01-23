<?php

namespace App\Filament\Resources\RatingTypes;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;
use App\Filament\Clusters\GlobalConfiguration\LeadConfigurationCluster;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\RatingTypes\Pages\ListRatingTypes;
use App\Filament\Resources\RatingTypes\Pages\CreateRatingType;
use App\Filament\Resources\RatingTypes\Pages\EditRatingType;
use App\Filament\Resources\RatingTypeResource\Pages;
use App\Filament\Resources\RatingTypeResource\RelationManagers;
use App\Models\RatingType;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RatingTypeResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = RatingType::class;

    protected static ?string $cluster = LeadConfigurationCluster::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Rating Types';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => ListRatingTypes::route('/'),
            'create' => CreateRatingType::route('/create'),
            'edit' => EditRatingType::route('/{record}/edit'),
        ];
    }
}
