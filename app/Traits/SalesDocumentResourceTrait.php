<?php

namespace App\Traits;

use App\Models\Tax;
use Filament\Forms;
use Filament\Tables;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
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
    use \App\Traits\ContactDetailsTrait;
    use \App\Traits\CompanyDetailsTrait;
    use \App\Traits\AddressDetailsTrait;
    use \App\Traits\ItemMasterTrait;
    use \App\Traits\AccountMasterDetailsTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : \App\Models\Quote::class;
    }

    // public SalesDocumentPreference $preferences;

    // public function mount($record): void
    // {
    //     parent::mount($record);

    //     // Load preferences once at mount time
    //     $this->preferences = SalesDocumentPreference::first();
    // }

    public static function getCommonFormFields(): array
    {

        $companyAccountFields = self::getAccountMasterDetailsTraitField();

        // if (self::resolveModelClass() === \App\Models\Quote::class) {
        //     $companyAccountFields = self::getCompanyDetailsTraitField();
        // } else {
        //     $companyAccountFields = self::getAccountMasterDetailsTraitField();
        // }
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
                        ->preload()
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
            
        Forms\Components\Section::make()
            ->extraAttributes([
                'class' => 'overflow-x-auto w-full no-gap-p6'
            ])
            ->schema([

                TableRepeater::make('items')
                    ->label(false)
                    ->columnSpan('full')
                    ->stackAt('100px')
                    ->streamlined()
                    ->headers([
                        Header::make('Item                                                                                          ')->width('350px'),
                        // Header::make('HAC/SAC                 ')->width('100px'),
                        Header::make('Quantity           ')->width('100px'),
                        Header::make('Price                      ')->width('100px'),
                        Header::make('Disc %        ')->width('100px'),
                        Header::make('Tax Rate %          ')->width('100px'),
                        Header::make('Taxable Amount                      ')->width('100px'),
                        Header::make(' ')->width('10px'),
                    ])
                    ->relationship('items')
                    ->extraAttributes(['style' => 'gap:0.5rem !important'])
                    ->schema([
                        Grid::make(2)
                            ->extraAttributes(['class' => 'no-gap'])
                            ->schema([
                                Forms\Components\Select::make('item_master_id')
                                    ->label(false)
                                    ->relationship('itemMaster', 'item_name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(2)
                                    ->extraAttributes([
                                        'class' => 'w-24 text-sm !rounded-none',
                                        'style' => 'font-size: 0.875rem; border-radius: 0;',
                                    ])
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
                                            $item = \App\Models\ItemMaster::with('taxes')->find($state);
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

                                Forms\Components\Textarea::make('description')
                                    ->label(false)
                                    ->rows(2)
                                    ->placeholder('Enter item description...')
                                    ->columnSpanFull()
                                    ->extraAttributes([
                                        'class' => 'w-24 h-16 text-sm',
                                        'style' => 'font-size: 0.875rem; border-radius: 0;',
                                    ]),
                                
                                
                            ]), 

                        Grid::make(1)
                            ->extraAttributes(['class' => 'no-gap'])
                            ->schema([ 
                                Forms\Components\TextInput::make('quantity')
                                    ->label(false)
                                    ->numeric()
                                    ->default(0)
                                    // ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        self::updateItemAmount($set, $get);
                                        self::updateTotals($set, $get);
                                    })
                                    ->extraAttributes([
                                        'class' => 'w-24 h-9 text-sm',
                                        'style' => 'font-size: 0.875rem; border-radius: 0; margin-top: -5px;',
                                    ]),
                                Forms\Components\TextInput::make('hsn_sac')
                                    ->label('Hsn/Sac')
                                    ->live()
                                    ->placeholder('HSN/SAC')
                                    ->extraAttributes([
                                        'class' => 'w-24 h-9 text-sm',
                                        'style' => 'font-size: 0.875rem; border-radius: 0; margin-top: 0px;',
                                    ]),
                            ]),
                        

                        Forms\Components\TextInput::make('price')
                            ->label(false)
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::updateItemAmount($set, $get);
                                self::updateTotals($set, $get);
                            })
                            ->extraAttributes([
                                'class' => 'w-24 h-9 text-sm',
                                'style' => 'font-size: 0.875rem; border-radius: 0; margin-top: -50px;',
                            ]),

                        Forms\Components\TextInput::make('discount')
                            ->label(false)
                            ->numeric()
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::updateItemAmount($set, $get);
                                self::updateTotals($set, $get);
                            })
                            ->extraAttributes([
                                'class' => 'w-24 h-9 text-sm',
                                'style' => 'font-size: 0.875rem; border-radius: 0; margin-top: -50px;',
                            ])
                            ->visible(function () {
                                return \App\Models\SalesDocumentPreference::first()?->discount_level === 'line_item';
                            }),

                        Forms\Components\Select::make('tax_rate')
                            ->label(false)
                            ->options(function (callable $get) {
                                $itemId = $get('item_master_id');

                                if (!$itemId) {
                                    return [];
                                }

                                $item = \App\Models\ItemMaster::with('taxes')->find($itemId);

                                if ($item && $item->taxes->isNotEmpty()) {
                                    return $item->taxes->mapWithKeys(function ($tax) {
                                        return [
                                            number_format($tax->total_rate, 2, '.', '') => number_format($tax->total_rate, 2) . '%',
                                        ];
                                    })->toArray();
                                }

                                // Fallback: list all taxes if no item taxes are found
                                return \App\Models\Tax::active()
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
                            ->placeholder('Tax Rate')
                            ->extraAttributes([
                                'class' => 'w-24 h-9 text-sm',
                                'style' => 'font-size: 0.875rem; border-radius: 0; margin-top: -50px;',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->label(false)
                            ->readOnly()
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
                            })
                            ->extraAttributes([
                                'class' => 'w-24 h-9 text-sm',
                                'style' => 'font-size: 0.875rem; border-radius: 0; margin-top: -50px;',
                            ]),
                    ])
                    ->extraAttributes([
                        'class' => 'text-sm',
                        'style' => 'font-size: 0.875rem; border-radius: 0;',
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
                    ->columnSpan('full')
                    ->addActionLabel('Add Item')
                    ->defaultItems(1),

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
                                    ->inlineLabel()
                                    ->readOnly()
                                    ->extraInputAttributes([
                                        'class' => 'text-right', // Added for right alignment
                                    ])
                                    ->formatStateUsing(function (?string $state): ?string {
                                        // Ensure state is not null and is a valid number before formatting
                                        if (is_numeric($state)) {
                                            return number_format((float) $state, 2, '.', '');
                                        }
                                        return $state; // Return original state if not numeric (e.g., null or empty string)
                                    })
                                    ->dehydrateStateUsing(fn ($state) =>
                                            $state !== null ? number_format((float) $state, 2, '.', '') : null
                                        ),
                                
                                Forms\Components\TextInput::make('transaction_discount')
                                    ->label('Transaction Discount')
                                    ->inlineLabel()
                                    ->visible(function () {
                                        return \App\Models\SalesDocumentPreference::first()?->discount_level === 'transaction';
                                    })
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('%'),
                                    
                                Forms\Components\TextInput::make('cgst')
                                        ->label('CGST')
                                        ->inlineLabel()
                                        ->readOnly()
                                        ->extraInputAttributes([
                                            'class' => 'text-right',
                                        ])
                                        ->formatStateUsing(function ($state) {
                                            if (is_numeric($state)) {
                                                return number_format((float) $state, 2, '.', '');
                                            }
                                            return $state;
                                        })
                                        ->visible(function (callable $get) {
                                            return self::shouldShowCgstSgst($get);
                                        }),

                                    Forms\Components\TextInput::make('sgst')
                                        ->label('SGST')
                                        ->inlineLabel()
                                        ->readOnly()
                                        ->extraInputAttributes([
                                            'class' => 'text-right',
                                        ])
                                        ->formatStateUsing(function ($state) {
                                            if (is_numeric($state)) {
                                                return number_format((float) $state, 2, '.', '');
                                            }
                                            return $state;
                                        })
                                        ->visible(function (callable $get) {
                                            return self::shouldShowCgstSgst($get);
                                        }),

                                    Forms\Components\TextInput::make('igst')
                                        ->label('IGST')
                                        ->inlineLabel()
                                        ->readOnly()
                                        ->extraInputAttributes([
                                            'class' => 'text-right',
                                        ])
                                        ->formatStateUsing(function ($state) {
                                            if (is_numeric($state)) {
                                                return number_format((float) $state, 2, '.', '');
                                            }
                                            return $state;
                                        })
                                        ->visible(function (callable $get) {
                                            return !self::shouldShowCgstSgst($get);
                                        }),

                                Forms\Components\TextInput::make('total')
                                    ->label('Total')
                                    ->inlineLabel()
                                    ->readOnly()
                                    ->extraInputAttributes([
                                        'class' => 'text-right', // Added for right alignment
                                    ])
                                    ->formatStateUsing(function ($state) {
                                        // Ensure state is not null and is a valid number before formatting
                                        if (is_numeric($state)) {
                                            return number_format((float) $state, 2, '.', '');
                                        }
                                        return $state; // Return original state if not numeric (e.g., null or empty string)
                                    })
                                    ->dehydrateStateUsing(fn ($state) =>
                                            $state !== null ? number_format((float) $state, 2, '.', '') : null
                                        ),
                            ])
                            ->columnSpan(1), // Right column
                    ])
                    ->columnSpan('full'), // Span the full width to align properly

                ]),

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
                $item = \App\Models\ItemMaster::with('taxes.components')->find($itemMasterId);
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
        Log::info('afterCreate hook is running.');
        $this->saveTaxDetailsFromModel();
    }

    public function afterSave(): void
    {
        Log::info('afterSave hook is running.');
        $this->saveTaxDetailsFromModel(); 
    }

    protected function saveTaxDetailsFromModel(): void
    {
        $record = $this->record ?? null;

        if (!$record) {
            Log::warning('saveTaxDetailsFromModel: Missing record.');
            return;
        }

        // Reload the record to ensure the 'items' relationship is up-to-date
        $record->load('items');
        $items = $record->items; // Get the items from the relationship, not the form state

        if ($items->isEmpty()) {
            Log::warning('saveTaxDetailsFromModel: No items found in record relationship.', [
                'record_id' => $record->id,
                'items_count' => $items->count(),
            ]);
            return;
        }

        Log::info('Starting saveTaxDetails from model relationship', [
            'record_id' => $record->id,
            'items_count' => $items->count(),
        ]);

        // Delete existing tax details
        TaxDetail::where('taxable_type', get_class($record))
            ->where('taxable_id', $record->id)
            ->delete();

        $useCgstSgst = $this->shouldShowCgstSgst(fn ($field) => data_get($this->form->getState(), $field));
        Log::info('Tax type determination', ['useCgstSgst' => $useCgstSgst]);

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

            $itemModel = \App\Models\ItemMaster::with('taxes.components')->find($itemMasterId);
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