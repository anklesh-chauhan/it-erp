<?php

namespace App\Traits;

use App\Models\Quote;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use App\Models\NumberSeries;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use App\Models\ItemMaster;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use App\Models\Tax;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\VerticalAlignment;
use App\Models\SalesDocumentPreference;
use App\Models\Address;
use App\Models\Organization;
use App\Models\TaxDetail;
use Illuminate\Database\Eloquent\Model;



trait SalesDocumentResourceTrait
{
    use ContactDetailsTrait;
    use CompanyDetailsTrait;
    use AddressDetailsTrait;
    use ItemMasterTrait;
    use AccountMasterDetailsTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : Quote::class;
    }

    public static function getCommonFormFields(): array
    {

        $companyAccountFields = self::getAccountMasterDetailsTraitField();

        return [
            Grid::make(4)
                ->schema([
                    TextInput::make('document_number')
                        ->label('Document Number')
                        ->default(fn () => NumberSeries::getNextNumber(static::resolveModelClass()))
                        ->disabled()
                        ->dehydrated(true),
                    DatePicker::make('date')
                        ->label('Date')
                        ->default(now()->toDateString())
                        ->required(),
                    Select::make('lead_id')
                        ->label('Lead')
                        ->relationship('lead', 'reference_code')
                        ->preload()
                        ->searchable(),
                    Select::make('sales_person_id')
                        ->label('Sales Person')
                        ->options(function () {
                            return User::all()->pluck('name', 'id')->toArray();
                        })
                        ->searchable()
                        ->preload()
                        ->placeholder('Select a sales person...')
                        ->required()
                        ->default(Auth::id()),

                ])->columnSpanFull(),

                ...$companyAccountFields,

                ...self::getContactDetailsTraitField(),
                ...self::getAddressDetailsTraitField(
                    fieldName: 'billing_address_id',
                    label: 'Billing Address',
                    relationshipName: 'billingAddress'
                ),
                Checkbox::make('has_shipping_address')
                    ->label('Add Shipping Address')
                    ->live()
                    ->default(false),
                Group::make()
                    ->schema(
                        self::getAddressDetailsTraitField(
                            fieldName: 'shipping_address_id',
                            label: 'Shipping Address',
                            relationshipName: 'shippingAddress'
                        )
                    )
                    ->hidden(fn (callable $get) => !$get('has_shipping_address') && !$get('shipping_address_id')),

            Section::make() // Two-column layout
            ->schema([
                Repeater::make('items')
                    ->relationship('items')
                    ->columnSpanFull()
                    ->label(false)
                    ->extraAttributes([
                        'class' => 'invoice-items-repeater',
                    ])
                    ->table([
                        TableColumn::make('Item                                                              ')
                            ->width('350px'),
                        TableColumn::make('Description                                                            ')->width('450px'),
                        TableColumn::make('Quantity           ')->width('100px'),
                        TableColumn::make('HSN/SAC'),
                        TableColumn::make('Price                      ')->width('100px'),
                        TableColumn::make('Disc %        ')->width('100px'),
                        TableColumn::make('Tax Rate %          ')->width('100px'),
                        TableColumn::make('Taxable Amount                      ')->width('100px'),
                        TableColumn::make(' ')->width('10px'),
                    ])
                    ->relationship('items')
                    ->schema([
                        Select::make('item_master_id')
                            ->label(false)
                            ->relationship('itemMaster', 'item_name')
                            ->searchable()
                            ->native(false) // Ensure Filament uses Popper.js instead of native select
                            ->preload()
                            ->required()
                            ->live() // Optional: for reactivity
                            ->getSearchResultsUsing(function (string $search): array {
                                // Fetch the search results
                                $items = ItemMaster::where('item_name', 'like', "%{$search}%")
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
                                    ->mutateDataUsing(function (array $data) {
                                        $data['item_code'] = $data['item_code'] ?? NumberSeries::getNextNumber(ItemMaster::class);
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
                                    $item = ItemMaster::with('taxes')->find($state);
                                    if ($item) {
                                        $set('description', $item?->description ?? '');
                                        $set('hsn_sac', $item?->hsn_code ?? ''); // Auto-fetch HSN/SAC
                                        $set('price', $item->selling_price ?? 0); // Auto-fetch Price (assuming 'sale_price' field in ItemMaster)
                                            // ✅ Automatically calculate total tax rate from related taxes
                                        $totalTaxRate = $item->taxes->sum('total_rate');
                                        $set('tax_rate', $totalTaxRate);
                                    } else {
                                        $set('description', '');
                                        $set('hsn_sac', '');
                                        $set('hsn_sac', '');
                                        $set('tax_rate', 0);
                                    }
                                } else {
                                    $set('description', '');
                                    $set('hsn_sac', '');
                                    $set('price', 0);
                                    $set('tax_rate', 0);
                                }
                            }),

                            Textarea::make('description')
                                ->label(false)
                                ->maxWidth(450)
                                ->rows(1)
                                ->placeholder('Enter item description...')
                                ->extraFieldWrapperAttributes(['class' => 'min-w-[550px]'])
                                ->columnSpanFull(),

                            TextInput::make('quantity')
                                ->label(false)
                                ->numeric()
                                ->default(0)
                                // ->required()
                                ->live()
                                ->extraFieldWrapperAttributes(['class' => 'min-w-[100px]'])
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::updateItemAmount($set, $get);
                                    self::updateTotals($set, $get);
                                }),

                            TextInput::make('hsn_sac')
                                ->label('Hsn/Sac')
                                ->live()
                                ->extraFieldWrapperAttributes(['class' => 'min-w-[100px]'])
                                ->placeholder('HSN/SAC'),

                            TextInput::make('price')
                                ->label(false)
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->live(onBlur: true)
                                ->extraFieldWrapperAttributes(['class' => 'min-w-[100px]'])
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::updateItemAmount($set, $get);
                                    self::updateTotals($set, $get);
                                }),

                            TextInput::make('discount')
                                ->label(false)
                                ->numeric()
                                ->default(0)
                                ->live(onBlur: true)
                                ->extraFieldWrapperAttributes(['class' => 'min-w-[100px]'])
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    self::updateItemAmount($set, $get);
                                    self::updateTotals($set, $get);
                                })
                                ->visible(function () {
                                    return SalesDocumentPreference::first()?->discount_level === 'line_item';
                                }),

                            Select::make('tax_rate')
                                ->label(false)
                                ->options(function (callable $get) {
                                    $itemId = $get('item_master_id');

                                    if (!$itemId) {
                                        return [];
                                    }

                                    $item = ItemMaster::with('taxes')->find($itemId);

                                    if ($item && $item->taxes->isNotEmpty()) {
                                        return $item->taxes->mapWithKeys(function ($tax) {
                                            return [
                                                number_format($tax->total_rate, 2, '.', '') => number_format($tax->total_rate, 2) . '%',
                                            ];
                                        })->toArray();
                                    }

                                    // Fallback: list all taxes if no item taxes are found
                                    return Tax::active()
                                        ->get()
                                        ->mapWithKeys(function ($tax) {
                                            return [
                                                number_format($tax->total_rate, 2, '.', '') => number_format($tax->total_rate, 2) . '%',
                                            ];
                                        })->toArray();
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->extraFieldWrapperAttributes(['class' => 'min-w-[100px]'])
                                ->placeholder('Tax Rate')
                                ->required(),

                            TextInput::make('amount')
                                ->label(false)
                                ->readOnly()
                                ->extraFieldWrapperAttributes(['class' => 'min-w-[100px]'])
                                ->dehydrated(true)
                                ->inputMode('decimal') // Enforce decimal input behavior
                                ->step('0.01')
                                ->extraInputAttributes([
                                    'min' => 0,
                                    'step' => '0.01', // Reinforce 2 decimal places in the HTML input
                                ])
                                ->formatStateUsing(function ($state): string {
                                    // Convert state to float to handle null, empty, or integer values
                                    $value = (float) ($state ?? 0);
                                    // Always format to 2 decimal places
                                    return number_format($value, 2, '.', '');
                                }),
                        ])
                    ->afterStateHydrated(function (callable $set, callable $get) {
                        self::updateTotals($set, $get);
                    })
                    ->afterStateUpdated(function (callable $set, callable $get, $record) {
                        self::updateTotals($set, $get);

                        $items = $get('items') ?? [];
                        foreach ($items as $key => $item) {
                            $path = "items.{$key}";
                            self::updateItemAmount(
                                fn ($field, $value) => $set("{$path}.{$field}", $value),
                                fn ($field) => $get("{$path}.{$field}")
                            );
                        }
                    })
                    ->columnSpanFull()
                    ->addActionLabel('Add Item'),

                Grid::make(4)
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->inlineLabel()
                                    ->readOnly()
                                    ->extraInputAttributes(['class' => 'text-right'])
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state),

                                TextInput::make('transaction_discount')
                                    ->label('Transaction Discount')
                                    ->inlineLabel()
                                    ->visible(fn () => SalesDocumentPreference::first()?->discount_level === 'transaction')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('%'),

                                TextInput::make('cgst')
                                    ->label('CGST')
                                    ->inlineLabel()
                                    ->readOnly()
                                    ->extraInputAttributes(['class' => 'text-right'])
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state)
                                    ->visible(fn (callable $get) => self::shouldShowCgstSgst($get)),

                                TextInput::make('sgst')
                                    ->label('SGST')
                                    ->inlineLabel()
                                    ->readOnly()
                                    ->extraInputAttributes(['class' => 'text-right'])
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state)
                                    ->visible(fn (callable $get) => self::shouldShowCgstSgst($get)),

                                TextInput::make('igst')
                                    ->label('IGST')
                                    ->inlineLabel()
                                    ->readOnly()
                                    ->extraInputAttributes(['class' => 'text-right'])
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state)
                                    ->visible(fn (callable $get) => !self::shouldShowCgstSgst($get)),

                                TextInput::make('total')
                                    ->label('Total')
                                    ->inlineLabel()
                                    ->readOnly()
                                    ->extraInputAttributes(['class' => 'text-right'])
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state),
                            ])
                            ->columnStart(4) // Push the entire group into the 4th column
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull()
            ])->columnSpanFull(),

                Textarea::make('description')
                    ->label('Description'),
                TextInput::make('currency')
                    ->required()
                    ->maxLength(3)
                    ->default('INR'),
                TextInput::make('payment_terms')
                    ->maxLength(255),
                TextInput::make('payment_method')
                    ->maxLength(255),
                TextInput::make('shipping_method')
                    ->maxLength(255),
                TextInput::make('shipping_cost')
                    ->maxLength(255),
                DatePicker::make('rejected_at'),
                DatePicker::make('canceled_at'),
                DatePicker::make('sent_at'),
            ];
    }

    public static function getCommonTableColumns(): array
    {
        return [
            TextColumn::make('document_number')
                ->label('Document Number'),
            TextColumn::make('lead.lead_code')
                ->label('Lead Code'),
            TextColumn::make('contactDetail.first_name')
                ->label('Contact Name'),
            TextColumn::make('company.name')
                ->label('Company Name'),
            TextColumn::make('date')
                ->label('Date')
                ->date(),
            TextColumn::make('subtotal')
                ->label('Subtotal')
                ->money('USD'),
            TextColumn::make('tax')
                ->label('Tax')
                ->money('USD'),
            TextColumn::make('total')
                ->label('Total')
                ->money('USD'),
            TextColumn::make('status')
                ->label('Status'),
        ];
    }

    private static function shouldShowCgstSgst(callable $get): bool
    {
        $billingAddressId = $get('billing_address_id');
        if (!$billingAddressId) {
            return false;
        }

        $billingAddress = Address::find($billingAddressId);
        $organization = Organization::first(); // Or however you get current org

        if (!$billingAddress || !$organization) {
            return false;
        }

        $organizationAddress = $organization->addresses()->first();
        $organizationState = $organizationAddress?->state;
        $billingState = $billingAddress->state;

        return $billingState && $organizationState &&
            strtolower(trim($billingState)) === strtolower(trim($organizationState));
    }


    protected function getDiscountLevel(): string
    {
        return SalesDocumentPreference::first()?->discount_level ?? 'none';
    }

    private static function updateItemAmount(callable $set, callable $get): void
    {
        $quantity = floatval($get('quantity') ?? 1);
        $price = floatval($get('price') ?? 0);
        $discount = floatval($get('discount') ?? 0);

        $discountAmount = ($quantity * $price) * ($discount / 100);
        $amount = ($quantity * $price) - $discountAmount;

        $set('amount', number_format($amount, 2, '.', ''));

    }

    private static function updateTotals(callable $set, callable $get): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;
        $cgstTotal = 0;
        $sgstTotal = 0;
        $igstTotal = 0;

        $useCgstSgst = self::shouldShowCgstSgst($get);

        foreach ($items as $key => $item) {
            $path = "items.{$key}";
            $quantity = floatval($get("{$path}.quantity") ?? 1);
            $price = floatval($get("{$path}.price") ?? 0);
            $discount = floatval($get("{$path}.discount") ?? 0);
            $taxRate = floatval($get("{$path}.tax_rate") ?? 0);
            $itemMasterId = $get("{$path}.item_master_id");

            $discountAmount = ($quantity * $price) * ($discount / 100);
            $amount = ($quantity * $price) - $discountAmount;
            $set("{$path}.amount", number_format($amount, 2, '.', ''));

            $subtotal += $amount;

            if ($itemMasterId && $taxRate) {
                $item = ItemMaster::with('taxes.components')->find($itemMasterId);
                $tax = $item->taxes->firstWhere('total_rate', $taxRate);
                if ($tax) {
                    $components = $tax->components;
                    if ($useCgstSgst) {
                        $cgstComponent = $components->firstWhere('type', 'CGST');
                        $sgstComponent = $components->firstWhere('type', 'SGST');
                        if ($cgstComponent) {
                            $cgstTotal += $amount * ($cgstComponent->rate / 100);
                        }
                        if ($sgstComponent) {
                            $sgstTotal += $amount * ($sgstComponent->rate / 100);
                        }
                    } else {
                        $igstComponent = $components->firstWhere('type', 'IGST');
                        if ($igstComponent) {
                            $igstTotal += $amount * ($igstComponent->rate / 100);
                        }
                    }
                }
            }
        }

        $transactionDiscount = floatval($get('transaction_discount') ?? 0);
        $transactionDiscountAmount = $subtotal * ($transactionDiscount / 100);
        $subtotalAfterDiscount = $subtotal - $transactionDiscountAmount;
        $total = $subtotalAfterDiscount + $cgstTotal + $sgstTotal + $igstTotal;

        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('cgst', number_format($cgstTotal, 2, '.', ''));
        $set('sgst', number_format($sgstTotal, 2, '.', ''));
        $set('igst', number_format($igstTotal, 2, '.', ''));
        $set('total', number_format($total, 2, '.', ''));
    }

    public function afterCreate(): void
    {
        $this->saveTaxDetailsFromModel();
    }

    public function afterSave(): void
    {
        $this->saveTaxDetailsFromModel();
    }

    protected function saveTaxDetailsFromModel(): void
    {
        $record = $this->record ?? null;

        if (!$record) {
            return;
        }

        // Reload the record to ensure the 'items' relationship is up-to-date
        $record->load('items');
        $items = $record->items; // Get the items from the relationship, not the form state

        if ($items->isEmpty()) {
            return;
        }

        // Delete existing tax details
        TaxDetail::where('taxable_type', get_class($record))
            ->where('taxable_id', $record->id)
            ->delete();

        $useCgstSgst = $this->shouldShowCgstSgst(fn ($field) => data_get($this->form->getState(), $field));

        foreach ($items as $item) {
            $quantity = floatval($item->quantity ?? 1);
            $price = floatval($item->price ?? 0);
            $discount = floatval($item->discount ?? 0);
            $taxRate = floatval($item->tax_rate ?? 0);
            $itemMasterId = $item->item_master_id ?? null;

            $discountAmount = ($quantity * $price) * ($discount / 100);
            $taxableAmount = ($quantity * $price) - $discountAmount;

            if (! $itemMasterId || ! $taxRate) {
                continue;
            }

            $itemModel = ItemMaster::with('taxes.components')->find($itemMasterId);
            if (! $itemModel) continue;

            $tax = $itemModel->taxes->first(function ($tax) use ($taxRate) {
                return abs($tax->total_rate - $taxRate) < 0.0001;
            });

            if (! $tax || $tax->components->isEmpty()) continue;

            $components = $tax->components;

            if ($useCgstSgst) {
                foreach (['CGST', 'SGST'] as $type) {
                    $component = $components->firstWhere('type', $type);
                    if ($component) {
                        TaxDetail::create([
                            'taxable_type' => get_class($record),
                            'taxable_id' => $record->id,
                            'tax_id' => $tax->id,
                            'tax_component_id' => $component->id,
                            'type' => $component->type,
                            'rate' => $component->rate,
                            'amount' => $taxableAmount * ($component->rate / 100),
                        ]);
                    }
                }
            } else {
                $igst = $components->firstWhere('type', 'IGST');
                if ($igst) {
                    TaxDetail::create([
                        'taxable_type' => get_class($record),
                        'taxable_id' => $record->id,
                        'tax_id' => $tax->id,
                        'tax_component_id' => $igst->id,
                        'type' => $igst->type,
                        'rate' => $igst->rate,
                        'amount' => $taxableAmount * ($igst->rate / 100),
                    ]);
                }
            }
        }
    }

}
