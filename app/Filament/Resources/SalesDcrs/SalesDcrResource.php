<?php

namespace App\Filament\Resources\SalesDcrs;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SalesDcrs\Pages\ListSalesDcrs;
use App\Filament\Resources\SalesDcrs\Pages\CreateSalesDcr;
use App\Filament\Resources\SalesDcrs\Pages\EditSalesDcr;
use App\Filament\Resources\SalesDcrResource\Pages;
use App\Filament\Resources\SalesDcrResource\RelationManagers;
use App\Models\SalesDcr;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesDcrResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = SalesDcr::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 30;
    protected static ?string $navigationLabel = 'Daily Call Report';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('jointwork_user_ids'),
                TextInput::make('visit_type_id')
                    ->numeric(),
                TextInput::make('tour_plan_id')
                    ->numeric(),
                TextInput::make('visit_route_ids'),
                TextInput::make('category_type')
                    ->maxLength(255),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('expense_total')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('visit_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tour_plan_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category_type')
                    ->searchable(),
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expense_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
            'index' => ListSalesDcrs::route('/'),
            'create' => CreateSalesDcr::route('/create'),
            'edit' => EditSalesDcr::route('/{record}/edit'),
        ];
    }
}
