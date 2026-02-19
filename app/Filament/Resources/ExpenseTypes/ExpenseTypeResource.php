<?php

namespace App\Filament\Resources\ExpenseTypes;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ExpenseTypes\Pages\ListExpenseTypes;
use App\Filament\Resources\ExpenseTypes\Pages\CreateExpenseType;
use App\Filament\Resources\ExpenseTypes\Pages\EditExpenseType;
use App\Filament\Resources\ExpenseTypeResource\Pages;
use App\Filament\Resources\ExpenseTypeResource\RelationManagers;
use App\Models\ExpenseType;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseTypeResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = ExpenseType::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Expense Config';
    protected static ?int $navigationSort = 1003;
    protected static ?string $navigationLabel = 'Expense Types';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('code')->required()->unique(ignoreRecord: true),
                Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('code'),
                IconColumn::make('is_active')->boolean(),
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
            'index' => ListExpenseTypes::route('/'),
            'create' => CreateExpenseType::route('/create'),
            'edit' => EditExpenseType::route('/{record}/edit'),
        ];
    }
}
