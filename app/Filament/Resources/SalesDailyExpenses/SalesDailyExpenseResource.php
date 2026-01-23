<?php

namespace App\Filament\Resources\SalesDailyExpenses;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SalesDailyExpenses\Pages\ListSalesDailyExpenses;
use App\Filament\Resources\SalesDailyExpenses\Pages\CreateSalesDailyExpense;
use App\Filament\Resources\SalesDailyExpenses\Pages\EditSalesDailyExpense;
use App\Filament\Resources\SalesDailyExpenseResource\Pages;
use App\Filament\Resources\SalesDailyExpenseResource\RelationManagers;
use App\Models\SalesDailyExpense;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesDailyExpenseResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = SalesDailyExpense::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Marketing & Field Sales';
    protected static ?int $navigationSort = 40;
    protected static ?string $navigationLabel = 'Daily Expense';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('serial_number')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('expense_date')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('transaction_date')
                    ->required(),
                TextInput::make('category_type')
                    ->maxLength(255),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('expense_type_id')
                    ->numeric(),
                TextInput::make('tour_plan_id')
                    ->numeric(),
                TextInput::make('rate_amount')
                    ->numeric(),
                TextInput::make('claim_amount')
                    ->numeric(),
                TextInput::make('approved_amount')
                    ->numeric(),
                TextInput::make('approver_id')
                    ->numeric(),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('expense_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('category_type')
                    ->searchable(),
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expense_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tour_plan_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rate_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('claim_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approver_id')
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
            'index' => ListSalesDailyExpenses::route('/'),
            'create' => CreateSalesDailyExpense::route('/create'),
            'edit' => EditSalesDailyExpense::route('/{record}/edit'),
        ];
    }
}
