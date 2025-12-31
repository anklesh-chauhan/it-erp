<?php

namespace App\Filament\Resources\IndustryTypes;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\IndustryTypes\Pages\ListIndustryTypes;
use App\Filament\Resources\IndustryTypes\Pages\CreateIndustryType;
use App\Filament\Resources\IndustryTypes\Pages\EditIndustryType;
use App\Filament\Resources\IndustryTypeResource\Pages;
use App\Filament\Resources\IndustryTypeResource\RelationManagers;
use App\Models\IndustryType;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndustryTypeResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = IndustryType::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Industry Types';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationParentItem = 'Company Config';

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
            'index' => ListIndustryTypes::route('/'),
            'create' => CreateIndustryType::route('/create'),
            'edit' => EditIndustryType::route('/{record}/edit'),
        ];
    }
}
