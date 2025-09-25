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
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;

trait SalesDocumentResourceTrait
{
    use ContactDetailsTrait;
    use CompanyDetailsTrait;
    use AddressDetailsTrait;
    use ItemMasterTrait;
    use AccountMasterDetailsTrait;
    use SalesDocumentPreferenceTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : Quote::class;
    }

    public static function getCommonFormFields(): array
    {

        $companyAccountFields = self::getAccountMasterDetailsTraitField();

        // Get the record from the route
        $record = request()->route('record');
        $modelClass = static::resolveModelClass();

        // If record is a string (e.g., ID), fetch the actual model instance
        if (is_string($record)) {
            $record = $modelClass::find($record);
        }
        // Determine discount mode: prioritize record's discount_mode, fallback to preference

        $recordDiscountMode = optional($record)->discount_mode;

        // ⚠️ CHANGE: Get discount mode from form state if available, otherwise from record or preference
        // Define the callable to get the discount mode value
        $discountModeCallable = fn(callable $get) => $get('discount_mode') 
            ?? optional($record)->discount_mode 
            ?? (SalesDocumentPreference::first()?->discount_level ?? 'none');

        // Now, define the visibility booleans using the callable's result
        $showLineItemDiscount = fn(callable $get) => in_array($discountModeCallable($get), ['line_item', 'both']);
        $showTransactionDiscount = fn(callable $get) => in_array($discountModeCallable($get), ['transaction', 'both']);


        // $showLineItemDiscount = SalesDocumentPreference::first()?->discount_level === 'line_item';

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
                    Hidden::make('discount_mode')
                        ->default(fn () => 
                            optional($record)->discount_mode 
                            ?? SalesDocumentPreference::first()?->discount_level 
                            ?? 'none'
                        ),

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
                    ->table(
                        fn (callable $get) => collect([
                            TableColumn::make('Item                                                                    ')
                                ->width('350px'),
                            TableColumn::make('Description                                                            ')->width('450px'),
                            TableColumn::make('Quantity           ')->width('100px'),
                            TableColumn::make('HSN/SAC'),
                            TableColumn::make('Price                      ')->width('100px'),
                            
                            // 🚀 Correctly execute the callable here with the $get from the closure
                            $showLineItemDiscount($get)
                                ? TableColumn::make('Disc %        ')->width('100px') 
                                : null,

                            TableColumn::make('Tax Rate %              ')->width('100px'),
                            TableColumn::make('Taxable Amount                      ')->width('100px'),
                            TableColumn::make(' ')->width('10px'),
                        ])->filter()->all()
                    )
                    ->relationship('items')
                    ->schema(
                        fn (callable $get) => collect([
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
                                        $set('tax_rate', number_format($totalTaxRate, 2, '.', ''));
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

                            $showLineItemDiscount($get)
                                ? TextInput::make('discount')
                                    ->label(false)
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::updateItemAmount($set, $get);
                                        self::updateTotals($set, $get);
                                    })
                                : null,

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
                        ])->filter()->all() // removes null entries safely
                    )
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
                Section::make()
                ->schema([
                    Grid::make(7)
                    ->schema([
                        Select::make('discount_type')
                            ->label('Disc Type')
                            ->options([
                                'percentage' => '%',
                                'amount'     => '₹',
                            ])
                            ->native(false)
                            ->default(fn () => $record && $record->discount_type ? $record->discount_type : 'percentage')
                            ->live() // make the select reactive
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                self::updateTotals($set, $get)
                            )
                            ->hidden(fn (callable $get) => !$showTransactionDiscount($get)),

                        TextInput::make('discount_value')
                            ->label('Disc Value')
                            ->numeric()
                            ->default(fn () => $record && is_numeric($record->discount_value) ? $record->discount_value : 0)
                                ->minValue(0)
                                ->maxValue(fn (callable $get) => $get('discount_type') === 'percentage' ? 100 : 99999999)
                            ->suffix(fn (callable $get) => $get('discount_type') === 'percentage' ? '%' : '₹')
                            ->live() // make the select reactive
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                self::updateTotals($set, $get)
                            )
                            ->hidden(fn (callable $get) => !$showTransactionDiscount($get)),

                            // Show computed discount amount (read-only)
                        TextInput::make('total_discount_amount') // 🚀 CHANGED field name
                            ->label('Total Disc Amt') // 🚀 CHANGED label
                            ->readOnly()
                            ->extraInputAttributes(['class' => 'text-right'])
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state),
                
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->readOnly()
                            ->extraInputAttributes(['class' => 'text-right'])
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state),

                        TextInput::make('cgst')
                            ->label('CGST')
                            ->readOnly()
                            ->extraInputAttributes(['class' => 'text-right'])
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state)
                            ->visible(fn (callable $get) => self::shouldShowCgstSgst($get)),

                        TextInput::make('sgst')
                            ->label('SGST')
                            ->readOnly()
                            ->extraInputAttributes(['class' => 'text-right'])
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state)
                            ->visible(fn (callable $get) => self::shouldShowCgstSgst($get)),

                        TextInput::make('igst')
                            ->label('IGST')
                            ->readOnly()
                            ->extraInputAttributes(['class' => 'text-right'])
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state)
                            ->visible(fn (callable $get) => !self::shouldShowCgstSgst($get)),

                        TextInput::make('total')
                            ->label('Total')
                            ->readOnly()
                            ->extraInputAttributes(['class' => 'text-right'])
                            ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format((float) $state, 2, '.', '') : $state),
                        
                        Hidden::make('discount_mode'),
                        Hidden::make('gross_total'),
                        Hidden::make('tax'),
                        // 🚀 NEW: Add a hidden field for the total line item discount
                        Hidden::make('total_line_item_discount'),
                        // 🚀 NEW: Rename old transaction_discount to just transaction_discount_amount to avoid conflicts
                        Hidden::make('transaction_discount'),
                    ])
                ]), // Empty section for spacing
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

        // Get the record from the route
        $record = request()->route('record');
        $modelClass = static::resolveModelClass();
        if (is_string($record)) {
            $record = $modelClass::find($record);
        }

        // Determine discount mode: prioritize record's discount_mode, fallback to preference
        $recordDiscountMode = optional($record)->discount_mode;
        $discountLevel = $get('discount_mode')
            ?? optional($record)->discount_mode
            ?? (SalesDocumentPreference::first()?->discount_level ?? 'none');

        $originalSubtotal = 0;
        $discountedSubtotal = 0;
        $cgstTotal = 0;
        $sgstTotal = 0;
        $igstTotal = 0;
        $transactionDiscountAmount = 0;
        $totalLineItemDiscount = 0; // 🚀 NEW: total line item discount

        $useCgstSgst = self::shouldShowCgstSgst($get);
        $lineData = [];

        // --- First loop: calculate gross + line-level discounts ---
        foreach ($items as $key => $item) {
            $path = "items.{$key}";
            $quantity = floatval($get("{$path}.quantity") ?? 1);
            $price = floatval($get("{$path}.price") ?? 0);
            $discount = floatval($get("{$path}.discount") ?? 0);

            $lineSubtotal = $quantity * $price;
            $originalSubtotal += $lineSubtotal;

            $lineDiscountAmount = 0;
            if ($discountLevel === 'line_item' || $discountLevel === 'both') {
                $lineDiscountAmount = $lineSubtotal * ($discount / 100);
                $totalLineItemDiscount += $lineDiscountAmount; // 🚀 NEW: sum line discounts
            }

            $lineAmount = $lineSubtotal - $lineDiscountAmount;
            $discountedSubtotal += $lineAmount;

            $set("{$path}.amount", number_format($lineAmount, 2, '.', ''));

            $lineData[$key] = [
                'gross' => $lineSubtotal,   // Keep gross for proportion allocation
                'amount' => $lineAmount,    // Net after line discount
                'tax_rate' => floatval($get("{$path}.tax_rate") ?? 0),
                'item_master_id' => $get("{$path}.item_master_id"),
            ];
        }

        // --- Transaction-level discount ---
        if ($discountLevel === 'transaction' || $discountLevel === 'both') {
            // Use record values in edit mode, otherwise form values
            $discountTypeRaw = $record && $record->discount_type ? $record->discount_type : ($get('discount_type') ?? 'percentage');
            $discountValue = $record && is_numeric($record->discount_value) ? floatval($record->discount_value) : floatval($get('discount_value') ?? 0);
            $discountType = strtolower(trim($discountTypeRaw));
            
            // Calculate transaction discount
            if ($discountType === 'percentage' || $discountType === '%') {
                $transactionDiscountAmount = $discountedSubtotal * ($discountValue / 100);
            } else {
                $transactionDiscountAmount = $discountValue;
            }

            // In edit mode, use stored transaction_discount if available
            if ($record && is_numeric($record->transaction_discount) && $discountLevel === 'both') {
                $transactionDiscountAmount = floatval($record->transaction_discount);
            }

            $discountedSubtotal = max(0, $discountedSubtotal - $transactionDiscountAmount);
        }

        // --- Calculate Taxes on final discounted amount (after both discounts if applicable) ---
        foreach ($lineData as $key => $data) {
            $proportion = $originalSubtotal > 0 ? ($data['gross'] / $originalSubtotal) : 0;
            $adjustedAmount = $data['amount']; // Start with line-level discounted amount

            if ($discountLevel === 'transaction' || $discountLevel === 'both') {
                $allocatedTransactionDiscount = $transactionDiscountAmount * $proportion;
                $adjustedAmount = max(0, $adjustedAmount - $allocatedTransactionDiscount);
            }

            if ($data['item_master_id'] && $data['tax_rate']) {
                $item = ItemMaster::with('taxes.components')->find($data['item_master_id']);
                $tax = $item?->taxes?->firstWhere('total_rate', $data['tax_rate']);
                if ($tax) {
                    foreach ($tax->components as $component) {
                        if ($useCgstSgst) {
                            if ($component->type === 'CGST') {
                                $cgstTotal += $adjustedAmount * ($component->rate / 100);
                            }
                            if ($component->type === 'SGST') {
                                $sgstTotal += $adjustedAmount * ($component->rate / 100);
                            }
                        } else {
                            if ($component->type === 'IGST') {
                                $igstTotal += $adjustedAmount * ($component->rate / 100);
                            }
                        }
                    }
                }
            }
        }

        $tax = $cgstTotal + $sgstTotal + $igstTotal;
        $total = $discountedSubtotal + $tax;

        // --- Set values in form state ---
        $set('gross_total', number_format($originalSubtotal, 2, '.', ''));
        $set('subtotal', number_format($discountedSubtotal, 2, '.', ''));
        
        // 🚀 NEW: Set the total line item discount and the total of all discounts
        $set('total_line_item_discount', number_format($totalLineItemDiscount, 2, '.', ''));
        $set('transaction_discount', number_format($transactionDiscountAmount, 2, '.', ''));
        $totalDiscount = $totalLineItemDiscount + $transactionDiscountAmount;
        $set('total_discount_amount', number_format($totalDiscount, 2, '.', ''));
        
        $set('cgst', number_format($cgstTotal, 2, '.', ''));
        $set('sgst', number_format($sgstTotal, 2, '.', ''));
        $set('igst', number_format($igstTotal, 2, '.', ''));
        $set('tax', number_format($tax, 2, '.', ''));
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

    // Reload items
    $record->load('items');
    $items = $record->items;

    if ($items->isEmpty()) {
        return;
    }

    // Delete existing tax details
    TaxDetail::where('taxable_type', get_class($record))
        ->where('taxable_id', $record->id)
        ->delete();

    $useCgstSgst = $this->shouldShowCgstSgst(fn ($field) => data_get($this->form->getState(), $field));
    $discountLevel = SalesDocumentPreference::first()?->discount_level ?? 'none';

    // --- Pre-calculation ---
    $grossTotal = 0;
    foreach ($items as $item) {
        $grossTotal += floatval($item->quantity ?? 1) * floatval($item->price ?? 0);
    }

    $transactionDiscountAmount = 0;
    $lineItemDiscountedTotal = $grossTotal;
    $totalLineItemDiscount = 0; // 🚀 NEW: Track line item discount total
    if ($discountLevel === 'line_item' || $discountLevel === 'both') {
        $lineItemDiscountedTotal = 0;
        foreach ($items as $item) {
            $lineGross = floatval($item->quantity ?? 1) * floatval($item->price ?? 0);
            $lineDiscount = $lineGross * (floatval($item->discount ?? 0) / 100);
            $lineItemDiscountedTotal += $lineGross - $lineDiscount;
            $totalLineItemDiscount += $lineDiscount; // 🚀 NEW: Add to line item discount total
        }
    }

    if ($discountLevel === 'transaction' || $discountLevel === 'both') {
        $discountTypeRaw = data_get($this->form->getState(), 'discount_type', 'percentage');
        $discountType = strtolower(trim($discountTypeRaw));
        $discountValue = floatval(data_get($this->form->getState(), 'discount_value', 0));
        
        $baseForTransactionDiscount = ($discountLevel === 'both') ? $lineItemDiscountedTotal : $grossTotal;

        if ($discountType === 'percentage' || $discountType === '%') {
            $transactionDiscountAmount = $baseForTransactionDiscount * ($discountValue / 100);
        } else {
            $transactionDiscountAmount = $discountValue;
        }
    }


    // --- Per item calculation ---
    foreach ($items as $item) {
        $quantity = floatval($item->quantity ?? 1);
        $price = floatval($item->price ?? 0);
        $discount = floatval($item->discount ?? 0);
        $taxRate = floatval($item->tax_rate ?? 0);
        $itemMasterId = $item->item_master_id ?? null;

        $lineGross = $quantity * $price;
        $taxableAmount = $lineGross;

        if ($discountLevel === 'line_item' || $discountLevel === 'both') {
            // line level discount only
            $lineDiscount = $lineGross * ($discount / 100);
            $taxableAmount = $lineGross - $lineDiscount;
        }

        if ($discountLevel === 'transaction' || $discountLevel === 'both') {
            // allocate proportional transaction discount
            $baseForTransactionDiscount = ($discountLevel === 'both') ? $lineItemDiscountedTotal : $grossTotal;
            $proportion = $baseForTransactionDiscount > 0 ? ($taxableAmount / $baseForTransactionDiscount) : 0;
            $allocatedDiscount = $transactionDiscountAmount * $proportion;
            $taxableAmount = max(0, $taxableAmount - $allocatedDiscount);
        }

        if (! $itemMasterId || ! $taxRate) {
            continue;
        }

        $itemModel = ItemMaster::with('taxes.components')->find($itemMasterId);
        if (! $itemModel) {
            continue;
        }

        $tax = $itemModel->taxes->first(function ($tax) use ($taxRate) {
            return abs($tax->total_rate - $taxRate) < 0.0001;
        });

        if (! $tax || $tax->components->isEmpty()) {
            continue;
        }

        $components = $tax->components;

        if ($useCgstSgst) {
            foreach (['CGST', 'SGST'] as $type) {
                $component = $components->firstWhere('type', $type);
                if ($component) {
                    TaxDetail::create([
                        'taxable_type' => get_class($record),
                        'taxable_id'   => $record->id,
                        'tax_id'       => $tax->id,
                        'tax_component_id' => $component->id,
                        'type'         => $component->type,
                        'rate'         => $component->rate,
                        'amount'       => $taxableAmount * ($component->rate / 100),
                    ]);
                }
            }
        } else {
            $igst = $components->firstWhere('type', 'IGST');
            if ($igst) {
                TaxDetail::create([
                    'taxable_type' => get_class($record),
                    'taxable_id'   => $record->id,
                    'tax_id'       => $tax->id,
                    'tax_component_id' => $igst->id,
                    'type'         => $igst->type,
                    'rate'         => $igst->rate,
                    'amount'       => $taxableAmount * ($igst->rate / 100),
                ]);
            }
        }
    }
}


}