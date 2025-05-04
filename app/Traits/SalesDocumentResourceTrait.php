<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Tables;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait SalesDocumentResourceTrait
{
    use \App\Traits\ContactDetailsTrait;
    use \App\Traits\CompanyDetailsTrait;
    use \App\Traits\AddressDetailsTrait;
    use \App\Traits\ItemMasterTrait;
    use \App\Traits\AccountMasterDetailsTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : \App\Models\Quote::class;
    }

    public static function getCommonFormFields(): array
    {

        $companyAccountFields = [];

        if (self::resolveModelClass() === \App\Models\Quote::class) {
            $companyAccountFields = self::getCompanyDetailsTraitField();
        } else {
            $companyAccountFields = self::getAccountMasterDetailsTraitField();
        }
        return [
            Forms\Components\Grid::make(4)
                ->schema([
                    Forms\Components\TextInput::make('document_number')
                        ->label('Document Number')
                        ->default(fn () => \App\Models\NumberSeries::getNextNumber(static::resolveModelClass()))
                        ->disabled()
                        ->dehydrated(true),
                    Forms\Components\DatePicker::make('date')
                        ->label('Date')
                        ->default(now()->toDateString())
                        ->required(),
                    Forms\Components\Select::make('lead_id')
                        ->label('Lead')
                        ->relationship('lead', 'reference_code')
                        ->searchable(),
                    Forms\Components\Select::make('sales_person_id')
                        ->label('Sales Person')
                        ->options(function () {
                            return \App\Models\User::all()->pluck('name', 'id')->toArray();
                        })
                        ->searchable()
                        ->preload()
                        ->placeholder('Select a sales person...')
                        ->required()
                        ->default(Auth::id()),

                ]),

                ...$companyAccountFields,

                ...self::getContactDetailsTraitField(),
                ...self::getAddressDetailsTraitField(
                    fieldName: 'billing_address_id',
                    label: 'Billing Address',
                    relationshipName: 'billingAddress'
                ),
                Forms\Components\Checkbox::make('has_shipping_address')
                    ->label('Add Shipping Address')
                    ->live()
                    ->default(false),
                Forms\Components\Group::make()
                    ->schema(
                        self::getAddressDetailsTraitField(
                            fieldName: 'shipping_address_id',
                            label: 'Shipping Address',
                            relationshipName: 'shippingAddress'
                        )
                    )
                    ->hidden(fn (callable $get) => !$get('has_shipping_address') && !$get('shipping_address_id')),

                Forms\Components\Section::make('Item Table')
                    ->schema([
                        TableRepeater::make('items')
                            ->headers([
                                Header::make('Item'),
                                Header::make('Quantity')->width('100px'),
                                Header::make('Price')->width('150px'),
                                Header::make('Disc %')->width('100px'),
                                Header::make('Amount')->width('150px'),
                                Header::make('Actions')->width('60px'),
                            ])
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('item_master_id')
                                            ->label(false)
                                            ->relationship('itemMaster', 'item_name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan(2)
                                            ->extraAttributes(['style' => 'gap: 0 !important;'])
                                            ->live() // Optional: for reactivity
                                            ->getSearchResultsUsing(function (string $search): array {
                                                // Fetch the search results
                                                $items = \App\Models\ItemMaster::where('item_name', 'like', "%{$search}%")
                                                    ->limit(50)
                                                    ->pluck('item_name', 'id')
                                                    ->toArray();

                                                return $items;
                                            })
                                            ->createOptionForm([
                                                ...self::getItemMasterTraitField()
                                            ])
                                            ->createOptionAction(function (Action $action) {
                                                return $action
                                                    ->modalHeading('Create New Item')
                                                    ->modalSubmitActionLabel('Create')
                                                    ->closeModalByClickingAway(false)
                                                    ->mutateFormDataUsing(function (array $data) {
                                                        $data['item_code'] = $data['item_code'] ?? \App\Models\NumberSeries::getNextNumber(\App\Models\ItemMaster::class);
                                                        return $data;
                                                    });
                                            }) // No visible() condition, always shown
                                            ->editOptionForm([
                                                ...self::getItemMasterTraitField() // Define the edit form fields
                                            ])
                                            ->editOptionAction(function (Action $action) {
                                                return $action
                                                    ->modalHeading('Edit Item')
                                                    ->modalSubmitActionLabel('Save')
                                                    ->closeModalByClickingAway(false)
                                                    ->visible(fn ($get) => !empty($get('item_master_id'))); // Show only if item_master_id is set
                                            })
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                // When an item is selected, fetch its description and update the Textarea
                                                if ($state) {
                                                    $item = \App\Models\ItemMaster::find($state);
                                                    $set('description', $item?->description ?? ''); // Set description or empty if not found
                                                } else {
                                                    $set('description', ''); // Clear description if no item is selected
                                                }
                                            }),

                                        Forms\Components\Textarea::make('description')
                                            ->label(false)
                                            ->rows(2)
                                            ->placeholder('Enter item description...')
                                            ->columnSpan(2)
                                            ->extraAttributes(['style' => 'gap: 0 !important;']),
                                    ])
                                    ->extraAttributes(['style' => 'gap: 0 !important;']) // Force inline style with !important
                                    ->columns(2), // Ensure the grid uses 2 columns
                                Forms\Components\TextInput::make('quantity')
                                    ->label(false)
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::updateItemAmount($set, $get);
                                        self::updateTotals($set, $get);
                                    })
                                    ->extraAttributes(['style' => 'text-align: right;']),
                                Forms\Components\TextInput::make('price')
                                    ->label(false)
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                                    ->dehydrateStateUsing(fn ($state) => round($state, 5))
                                    ->extraAttributes(['step' => '0.00001'])
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::updateItemAmount($set, $get);
                                        self::updateTotals($set, $get);
                                    })
                                    ->extraAttributes(['style' => 'text-align: right;']),
                                Forms\Components\TextInput::make('discount')
                                    ->label(false)
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::updateItemAmount($set, $get);
                                        self::updateTotals($set, $get);
                                    }),
                                Forms\Components\TextInput::make('amount')
                                    ->label(false)
                                    ->numeric()
                                    ->default(0)
                                    ->readOnly()
                                    ->dehydrated(true)
                                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                                    ->dehydrateStateUsing(fn ($state) => round($state, 5))
                                    ->extraAttributes(['style' => 'text-align: right;']),
                            ])
                            ->afterStateHydrated(function (callable $set, callable $get) {
                                $items = $get('items') ?? [];
                                foreach ($items as $key => $item) {
                                    $path = "items.{$key}";
                                    self::updateItemAmount(
                                        fn ($field, $value) => $set("{$path}.{$field}", $value),
                                        fn ($field) => $get("{$path}.{$field}")
                                    );
                                }
                                self::updateTotals($set, $get);
                            })
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                self::updateTotals($set, $get);
                            })
                            ->columnSpan('full')
                            ->addActionLabel('Add New Row')
                            ->deleteAction(fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation())
                            ->defaultItems(1),
                    ]),

                    Forms\Components\Grid::make(4) // Two-column layout
                    ->schema([
                        Forms\Components\Placeholder::make('') // Empty left column to push totals right
                            ->content(''),
                        Forms\Components\Placeholder::make('') // Empty left column to push totals right
                        ->content(''),
                        Forms\Components\Placeholder::make('') // Empty left column to push totals right
                        ->content(''),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->live()
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
                                Forms\Components\TextInput::make('tax')
                                    ->label('Tax')
                                    ->numeric()
                                    ->live()
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
                                Forms\Components\TextInput::make('total')
                                    ->label('Total')
                                    ->numeric()
                                    ->live()
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
                            ])
                            ->columnSpan(1), // Right column
                    ])
                    ->columnSpan('full'), // Span the full width to align properly


                Forms\Components\Textarea::make('description')
                    ->label('Description'),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('INR'),
                Forms\Components\TextInput::make('payment_terms')
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_method')
                    ->maxLength(255),
                Forms\Components\TextInput::make('shipping_method')
                    ->maxLength(255),
                Forms\Components\TextInput::make('shipping_cost')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('rejected_at'),
                Forms\Components\DatePicker::make('canceled_at'),
                Forms\Components\DatePicker::make('sent_at'),
            ];
    }

    public static function getCommonTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('document_number')
                ->label('Document Number'),
            Tables\Columns\TextColumn::make('lead.lead_code')
                ->label('Lead Code'),
            Tables\Columns\TextColumn::make('contactDetail.first_name')
                ->label('Contact Name'),
            Tables\Columns\TextColumn::make('company.name')
                ->label('Company Name'),
            Tables\Columns\TextColumn::make('date')
                ->label('Date')
                ->date(),
            Tables\Columns\TextColumn::make('subtotal')
                ->label('Subtotal')
                ->money('USD'),
            Tables\Columns\TextColumn::make('tax')
                ->label('Tax')
                ->money('USD'),
            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->money('USD'),
            Tables\Columns\TextColumn::make('status')
                ->label('Status'),
        ];
    }

    private static function updateItemAmount(callable $set, callable $get): void
    {
        $quantity = floatval($get('quantity') ?? 1);
        $price = floatval($get('price') ?? 0);
        $discount = floatval($get('discount') ?? 0);

        $discountAmount = ($quantity * $price) * ($discount / 100);
        $amount = ($quantity * $price) - $discountAmount;

        $set('amount', $amount);
        Log::info('Amount Updated:', ['quantity' => $quantity, 'price' => $price, 'discount' => $discount, 'amount' => $amount]);
    }

    private static function updateTotals(callable $set, callable $get): void
    {
        $items = $get('items') ?? [];
        Log::info('Items in updateTotals:', $items);

        foreach ($items as $key => $item) {
            $path = "items.{$key}";
            $quantity = floatval($get("{$path}.quantity") ?? 1);
            $price = floatval($get("{$path}.price") ?? 0);
            $discount = floatval($get("{$path}.discount") ?? 0);

            $discountAmount = ($quantity * $price) * ($discount / 100);
            $amount = ($quantity * $price) - $discountAmount;
            $set("{$path}.amount", $amount);
        }

        $updatedItems = $get('items') ?? [];
        $subtotal = collect($updatedItems)->sum(fn ($item) => floatval($item['amount'] ?? 0));
        $taxRate = 0.10; // Adjust as needed
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        Log::info('Calculated Totals:', ['subtotal' => $subtotal, 'tax' => $tax, 'total' => $total]);

        $set('subtotal', $subtotal);
        $set('tax', $tax);
        $set('total', $total);
    }
}
