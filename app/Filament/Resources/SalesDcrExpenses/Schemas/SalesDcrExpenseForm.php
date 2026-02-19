<?php

namespace App\Filament\Resources\SalesDcrExpenses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class SalesDcrExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('sales_dcr_id')
                    ->relationship('dcr', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('expense_type_id')
                    ->relationship('expenseType', 'name')
                    ->required(),

                Forms\Components\Select::make('transport_mode_id')
                    ->relationship('transportMode', 'name')
                    ->nullable(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric(),

                Forms\Components\TextInput::make('rate')
                    ->numeric(),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),

                Forms\Components\Toggle::make('is_auto_calculated')
                    ->disabled(),

                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
