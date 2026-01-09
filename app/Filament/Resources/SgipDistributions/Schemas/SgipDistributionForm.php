<?php

namespace App\Filament\Resources\SgipDistributions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountMaster;
use App\Models\ItemMaster;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\User;
use Filament\Schemas\Components\Grid;

class SgipDistributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Visit Details')
                    ->columns(4)
                    ->schema([
                        Select::make('user_id')
                            ->label('Sales Employee')
                            ->relationship('user', 'id')
                            ->getOptionLabelFromRecordUsing(
                                fn (?User $user) =>
                                    $user
                                        ? ($user->employee?->full_name ?? $user->email)
                                        : '—'
                            )
                            ->default(Auth::id())
                            ->disabled()
                            ->dehydrated(),

                        Select::make('account_master_id')
                            ->label('Customer')
                            ->options(
                                AccountMaster::query()
                                    ->whereHas('typeMaster', fn ($q) => $q->where('name', 'Retail Customer'))
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),

                        DatePicker::make('visit_date')
                            ->native(false)
                            ->required(),

                        TextInput::make('status')
                            ->disabled()
                            ->default('draft'),
                    ])->columnSpanFull(),

                /* ===============================
                | ITEMS
                =============================== */
                Section::make('Samples / Gifts / Inputs')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->addable()
                            ->deletable()
                            ->columns(5)
                            ->schema([
                                Select::make('item_master_id')
                                    ->label('Item')
                                    ->options(
                                        ItemMaster::query()
                                            ->pluck('item_name', 'id')
                                    )
                                    ->columnSpan(2)
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {

                                        if (! $state) {
                                            return;
                                        }

                                        $item = ItemMaster::find($state);

                                        if (! $item) {
                                            return;
                                        }

                                        // ✅ Fetch selling price
                                        $set('unit_value', $item->selling_price ?? 0);
                                    }),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if (! $state) {
                                            return;
                                        }

                                        $set('total_value', $state * ($get('unit_value') ?? 0));
                                    }),

                                TextInput::make('unit_value')
                                    ->numeric()
                                    ->reactive()
                                    ->required(),

                                TextInput::make('total_value')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->reactive()
                                    ->afterStateHydrated(
                                        fn ($set, Get $get) =>
                                            $set(
                                                'total_value',
                                                ($get('quantity') ?? 0) * ($get('unit_value') ?? 0)
                                            )
                                    ),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
