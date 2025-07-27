<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\CityPinCode;


trait AddressDetailsTrait
{
    /**
     * Get common form fields for address details.
     *
     * @param string $fieldName The name of the address field (e.g., 'billing_address_id', 'shipping_address_id')
     * @param string $label The label for the field (e.g., 'Billing Address', 'Shipping Address')
     * @param string|null $relationshipName Optional custom relationship name (defaults to 'address')
     * @param string|null $defaultAddressType Optional address type to filter default (e.g., 'Billing', 'Shipping')
     * @return array
     */

    public static function getAddressDetailsTraitField(
        string $fieldName = 'address_id',
        string $label = 'Address',
        ?string $relationshipName = null,
        ?string $defaultAddressType = null
    ): array {

        $relationshipName = $relationshipName ?? 'address';

        return [
            Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make($fieldName)
                                ->label($label)
                                ->relationship($relationshipName, 'street', function ($query, callable $get) {
                                    // Get contact_id and account_master_id from the form
                                    $contactId = $get('contact_detail_id');
                                    $accountMasterId = $get('account_master_id');

                                    // Base query
                                    $query = \App\Models\Address::query();

                                    // Filter by contact's addresses if contact_id is present
                                    if ($contactId) {
                                        $contact = \App\Models\ContactDetail::with('addresses')->find($contactId);
                                        if ($contact) {
                                            $query->whereIn('id', $contact->addresses->pluck('id'));
                                        }
                                    }

                                    // Filter by account master's addresses if account_master_id is present
                                    if ($accountMasterId) {
                                        $query->where('addressable_id', $accountMasterId)
                                            ->where('addressable_type', \App\Models\AccountMaster::class);
                                    }

                                    return $query;
                                })
                                ->getSearchResultsUsing(function (string $search, callable $get) {
                                    // Get account_master_id for filtering
                                    $accountMasterId = $get('account_master_id');
                                    $query = \App\Models\Address::query();

                                    // Filter by account master's addresses if account_master_id is present
                                    if ($accountMasterId) {
                                        $query->where('addressable_id', $accountMasterId)
                                            ->where('addressable_type', \App\Models\AccountMaster::class);
                                    }

                                    // Search across address fields
                                    $query->where(function ($q) use ($search) {
                                        $q->where('street', 'like', "%{$search}%")
                                        ->orWhere('city', 'like', "%{$search}%")
                                        ->orWhere('state', 'like', "%{$search}%")
                                        ->orWhere('country', 'like', "%{$search}%")
                                        ->orWhere('address_type', 'like', "%{$search}%");
                                    });

                                    return $query->get()
                                        ->mapWithKeys(fn ($address) => [
                                            $address->id => "{$address->street}, {$address->city}, {$address->state} — {$address->address_type}"
                                        ]);
                                })
                                ->default(function (callable $get) use ($defaultAddressType) {
                                    // Automatically select an address based on account_master_id
                                    $accountMasterId = $get('account_master_id');
                                    if ($accountMasterId) {
                                        $accountMaster = \App\Models\AccountMaster::with('addresses')->find($accountMasterId);
                                        if ($accountMaster && $accountMaster->addresses->isNotEmpty()) {
                                            // Prefer address with the defaultAddressType if specified
                                            if ($defaultAddressType) {
                                                $defaultAddress = $accountMaster->addresses->where('address_type', $defaultAddressType)->first();
                                                if ($defaultAddress) {
                                                    return $defaultAddress->id;
                                                }
                                            }
                                            // Fallback to the first address if no defaultAddressType is specified or found
                                            return $accountMaster->addresses->first()->id;
                                        }
                                    }
                                    return null;
                                })
                                ->searchable()
                                ->nullable()
                                ->reactive()
                                ->createOptionForm([
                                    Forms\Components\Hidden::make('contact_detail_id')
                                        ->default(fn (callable $get) => $get('contact_detail_id')) // ✅ Auto-set `contact_id`
                                        ->dehydrated(),
                                    Forms\Components\Hidden::make('addressable_id')
                                        ->default(fn (callable $get) => $get('account_master_id'))
                                        ->dehydrated(),
                                    Forms\Components\Hidden::make('addressable_type')
                                        ->default(\App\Models\AccountMaster::class)
                                        ->dehydrated(),
                                    Forms\Components\Select::make('type_master_id')
                                        ->label('Address Type')
                                        ->options(
                                            \App\Models\TypeMaster::query()
                                                ->ofType(\App\Models\Address::class) // Filter by the `Address` model
                                                ->pluck('name', 'id') // Get the name and ID for the dropdown
                                        )
                                        ->required()
                                        ->searchable(),

                                    Forms\Components\TextInput::make('street')->required(),
                                    Forms\Components\TextInput::make('area_town')->required(),

                                    Forms\Components\TextInput::make('pin_code')
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                            if (!$get('city_id')) {
                                                $pinCodeDetails = CityPinCode::where('pin_code', $state)->first();
                                                if ($pinCodeDetails) {
                                                    $set('area_town', $pinCodeDetails->area_town);
                                                    $set('city_id', $pinCodeDetails->city_id);
                                                    $set('state_id', $pinCodeDetails->state_id);
                                                    $set('country_id', $pinCodeDetails->country_id);
                                                }
                                            }
                                        }),

                                    Forms\Components\Select::make('city_id')
                                        ->relationship('city', 'name')
                                        ->searchable(),

                                    Forms\Components\Select::make('state_id')
                                        ->relationship('state', 'name')
                                        ->searchable(),

                                    Forms\Components\Select::make('country_id')
                                        ->relationship('country', 'name')
                                        ->searchable(),
                                ])
                                ->createOptionUsing(function (array $data, callable $get, callable $set) use ($fieldName)  {
                                    $data['addressable_id'] = $get('account_master_id');
                                    $data['addressable_type'] = \App\Models\AccountMaster::class;
                                    $data['contact_detail_id'] = $get('contact_detail_id');
                                    $data['company_id'] = $get('company_id');

                                    // Create the address
                                    $address = \App\Models\Address::create($data);

                                    // Debug: Log the created address
                                    \Illuminate\Support\Facades\Log::info('getAddressDetailsTraitField: Address created', [
                                        'address_id' => $address->id,
                                        'account_master_id' => $data['addressable_id'],
                                        'addressable_type' => $data['addressable_type'],
                                        'contact_detail_id' => $data['contact_detail_id'],
                                        'company_id' => $data['company_id'],
                                    ]);

                                    // Update the form state
                                    $set($fieldName, $address->id);

                        return $address->id;            
                                })
                                ->createOptionAction(fn (Forms\Components\Actions\Action $action) =>
                                    $action->hidden(fn (callable $get) => $get($fieldName) !== null) // ✅ Hide "Create" button when a contact is selected
                                )
                                ->suffixAction(
                                    Action::make('editAddress')
                                        ->icon('heroicon-o-pencil')
                                        ->modalHeading('Edit Address')
                                        ->modalSubmitActionLabel('Update Address')
                                        ->form(fn (callable $get) => [
                                            Forms\Components\Hidden::make('company_id')
                                                ->default(fn (callable $get) => $get('company_id'))
                                                ->dehydrated(),

                                            Forms\Components\Select::make('address_type')
                                                ->options([
                                                    'Company' => 'Company',
                                                    'Home' => 'Home',
                                                    'Office' => 'Office',
                                                    'Other' => 'Other',
                                                ])
                                                ->required()
                                                ->label('Address Type')
                                                ->default(\App\Models\Address::find($get($fieldName))?->address_type),

                                            Forms\Components\TextInput::make('street')
                                                ->default(\App\Models\Address::find($get($fieldName))?->street)
                                                ->required(),

                                            Forms\Components\TextInput::make('area_town')
                                                ->default(\App\Models\Address::find($get($fieldName))?->area_town)
                                                ->required(),

                                            Forms\Components\TextInput::make('pin_code')
                                                ->default(\App\Models\Address::find($get($fieldName))?->pin_code)
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                                    $pinCodeDetails = CityPinCode::where('pin_code', $state)->first();
                                                    if ($pinCodeDetails) {
                                                        $set('area_town', $pinCodeDetails->area_town);
                                                        $set('city_id', $pinCodeDetails->city_id);
                                                        $set('state_id', $pinCodeDetails->state_id);
                                                        $set('country_id', $pinCodeDetails->country_id);
                                                    }
                                                }),

                                            Forms\Components\Select::make('city_id')
                                                ->relationship('city', 'name')
                                                ->searchable()
                                                ->default(\App\Models\Address::find($get($fieldName))?->city_id)
                                                ->reactive()
                                                ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                                    $cityDetails = \App\Models\City::find($state);
                                                    if ($cityDetails) {
                                                        $set('state_id', $cityDetails->state_id);
                                                        $set('country_id', $cityDetails->country_id);
                                                    }
                                                }),

                                            Forms\Components\Select::make('state_id')
                                                ->relationship('state', 'name')
                                                ->searchable()
                                                ->default(\App\Models\Address::find($get($fieldName))?->state_id),

                                            Forms\Components\Select::make('country_id')
                                                ->relationship('country', 'name')
                                                ->searchable()
                                                ->default(\App\Models\Address::find($get($fieldName))?->country_id),
                                        ])
                                        ->action(function (array $data, callable $get, callable $set) use ($fieldName) {
                                            $address = \App\Models\Address::find($get($fieldName));
                                            if ($address) {
                                                $address->update($data);

                                                // ✅ Update the state after editing
                                                $set($fieldName, $address->id);

                                                Notification::make()
                                                    ->title('Address Updated')
                                                    ->success()
                                                    ->send();
                                            }
                                        })
                                        ->requiresConfirmation()
                                        ->visible(fn (callable $get) => $get($fieldName))
                                )
                                ->default(function (callable $get) use ($fieldName, $defaultAddressType) {
                                    // Use the existing value if set
                                    if ($addressId = $get($fieldName)) {
                                        return $addressId;
                                    }
                                    // Otherwise, select an address based on contact and optional address type
                                    if ($contactId = $get('contact_detail_id')) {
                                        $contact = \App\Models\ContactDetail::with('addresses')->find($contactId);
                                        if ($defaultAddressType) {
                                            $address = $contact?->addresses->firstWhere('address_type', $defaultAddressType);
                                            return $address?->id;
                                        }
                                        return $contact?->addresses->first()?->id;
                                    }
                                    return null;
                                })
                                ->afterStateHydrated(function (callable $set, $state) use ($fieldName) {
                                    // Ensure the field is set to the model's value if it exists
                                    if ($state) {
                                        $set($fieldName, $state);
                                    }
                                })
                                ->getOptionLabelUsing(function ($value) {
                                    $address = \App\Models\Address::with('addressType')->find($value);
                                    return $address ? "{$address->addressType?->name} - {$address->street}" : 'Unknown Address';
                                })
                                ->preload(),

                                Forms\Components\Placeholder::make($label)
                                    ->hidden(fn (callable $get) => !$get($fieldName)) // Hide if no contact or address is selected
                                    ->content(function (callable $get) use ($fieldName) {
                                        $contact = \App\Models\ContactDetail::find($get('contact_detail_id'));
                                        $address = $get($fieldName)
                                            ? \App\Models\Address::find($get($fieldName)) // Use the selected address if available
                                            : $contact?->addresses->first(); // Otherwise, use the first address from the contact

                                        // Extract clean city, state, and country names
                                        $city = $address?->city?->name ?? $address?->city;
                                        $state = $address?->state?->name ?? $address?->state;
                                        $country = $address?->country?->name ?? $address?->country;

                                        $addressDetails = $address
                                            ? "📍 {$address->street}, {$city}, {$state}, {$country}, {$address->pin_code}"
                                            : 'No address details available.';

                                        return "{$addressDetails}";
                                    }),
                            ]),
        ];
    }
}
