<?php

namespace App\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use App\Models\ContactDetail;
use App\Models\AccountMaster;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Exception;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait AccountMasterDetailsTrait
{
    /**
     * Get common form fields for AccountMaster.
     *
     * @return array
     */
    public static function getAccountMasterDetailsTraitField(): array
    {
        return [
            Grid::make(2)
                    ->schema([
                        Select::make('account_master_id')
                            ->label('Business Details')
                            ->options(function (callable $get) {
                                $contactId = $get('contact_detail_id');

                                // Check if ApprovalRule exists for AccountMaster module
                                $approvalRuleExists = \App\Models\ApprovalRule::where('module', 'AccountMaster')->where('active', true)->exists();

                                if ($contactId) {
                                    $contact = \App\Models\ContactDetail::with('accountMasters')->find($contactId);
                                    if (!$contact) return [];

                                    $query = $contact->accountMasters();

                                    // If approval rule exists, filter only approved account masters
                                    if ($approvalRuleExists) {
                                        $query = $query->whereHas('approvals', function ($q) {
                                            $q->where('status', 'approved');
                                        });
                                    }

                                    return $query->pluck('account_masters.name', 'account_masters.id')->toArray();
                                }

                                // No contact selected
                                $query = \App\Models\AccountMaster::query();

                                if ($approvalRuleExists) {
                                    $query = $query->whereHas('approvals', function ($q) {
                                        $q->where('status', 'approved');
                                    });
                                }

                                return $query->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->nullable()
                            ->preload()
                            ->live()
                            ->extraAttributes(fn (callable $get) => $get('account_master_id') ? ['class' => 'hide-create-button'] : [])
                            ->createOptionForm(fn (callable $get) => $get('account_master_id')
                                ? [
                                    Placeholder::make('info')
                                        ->label('Info')
                                        ->content('The selected contact already belongs to an account master. Creating a new account master is not allowed.')
                                    ]
                                : [
                                    Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->label('Account Master Name'),

                                        TextInput::make('email')
                                            ->email()
                                            ->nullable()
                                            ->label('Account Master Email'),

                                        TextInput::make('website')
                                            ->url()
                                            ->nullable()
                                            ->label('Website'),

                                        TextInput::make('phone_number')
                                            ->label('Phone Number')
                                            ->nullable(),

                                        Select::make('industry_type_id')
                                            ->relationship('industryType', 'name')
                                            ->model(AccountMaster::class)
                                            ->searchable()
                                            ->nullable()
                                            ->label('Industry Type')
                                            ->preload(),

                                        TextInput::make('no_of_employees')
                                            ->maxLength(255)
                                            ->nullable(),

                                        Select::make('owner_id')
                                            ->relationship('owner', 'name')
                                            ->model(AccountMaster::class)
                                            ->default(fn () => Auth::id())
                                            ->required()
                                            ->label('Owner'),

                                        // 👇 Hidden field for type_master_id
                                        Hidden::make('type_master_id')
                                            ->default(fn (callable $get) => 8), // Set static or dynamic default

                                    ])
                                ])
                            ->createOptionUsing(function (array $data, callable $set, callable $get) {
                                $accountMaster = AccountMaster::create($data);

                                // Attach to contact if contact_detail_id exists
                                if ($contactId = $get('contact_detail_id')) {
                                    $accountMaster->contactDetails()->syncWithoutDetaching([$contactId]);
                                }

                                $set('account_master_id', $accountMaster->id);
                                return $accountMaster->id;
                            })
                            ->createOptionAction(fn (Action $action) =>
                                $action->hidden(fn (callable $get) => $get('account_master_id') !== null) // Hide "Create" button when a contact is selected
                            )
                            ->suffixAction(
                                Action::make('editAccountMaster')
                                    ->icon('heroicon-o-pencil')
                                    ->modalHeading('Edit Account Master')
                                    ->modalSubmitActionLabel('Update Account Master')
                                    ->schema(fn (callable $get) => [
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->default(AccountMaster::find($get('account_master_id'))?->name)
                                                    ->required()
                                                    ->label('Account Master Name'),

                                                TextInput::make('email')
                                                    ->email()
                                                    ->default(AccountMaster::find($get('account_master_id'))?->email)
                                                    ->nullable()
                                                    ->label('Account Master Email'),

                                                TextInput::make('website')
                                                    ->url()
                                                    ->default(AccountMaster::find($get('account_master_id'))?->website)
                                                    ->nullable()
                                                    ->label('Website'),

                                                TextInput::make('phone_number')
                                                    ->default(AccountMaster::find($get('account_master_id'))?->phone_number)
                                                    ->nullable()
                                                    ->label('Phone Number'),

                                                Select::make('industry_type_id')
                                                    ->relationship('industryType', 'name')
                                                    ->model(AccountMaster::class)
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(fn () => AccountMaster::find($get('account_master_id'))?->industry_type_id),

                                                TextInput::make('no_of_employees')
                                                    ->default(AccountMaster::find($get('account_master_id'))?->no_of_employees)
                                                    ->maxLength(255)
                                                    ->label('Number of Employees'),
                                            ]),
                                    ])
                                    ->action(function (array $data, callable $get) {
                                        $accountMaster = AccountMaster::find($get('account_master_id'));

                                        if ($accountMaster) {
                                            $accountMaster->update([
                                                'name' => $data['name'] ?? $accountMaster->name,
                                                'email' => $data['email'] ?? $accountMaster->email,
                                                'website' => $data['website'] ?? $accountMaster->website,
                                                'phone_number' => $data['phone_number'] ?? $accountMaster->phone_number,
                                                'industry_type_id' => $data['industry_type_id'] ?? $accountMaster->industry_type_id,
                                                'no_of_employees' => $data['no_of_employees'] ?? $accountMaster->no_of_employees,
                                            ]);

                                            Notification::make()
                                                ->title('Account Master Updated')
                                                ->success()
                                                ->send();
                                        }
                                    })
                                    ->requiresConfirmation()
                                    ->visible(fn (callable $get) => $get('account_master_id'))
                            )
                            // After state update for account_master_id
                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        if ($state) {
                            $accountMaster = AccountMaster::with('addresses')->find($state);

                            if (!$accountMaster) {
                                $set('contact_detail_id', null);
                                $set('show_account_master_info', null);
                                $set('billing_address_id', null); // Clear address if no account master
                                return;
                            }

                            // Auto-select address
                            if ($accountMaster->addresses->isNotEmpty()) {
                                // Prefer address with 'Billing' type if specified
                                $defaultAddress = $accountMaster->addresses->where('address_type', 'Billing')->first()
                                    ?? $accountMaster->addresses->first(); // Fallback to first address
                                $set('billing_address_id', $defaultAddress->id);

                                // Debug: Log the selected address
                                Log::info('getAccountMasterDetailsTraitField: Auto-selected address', [
                                    'account_master_id' => $state,
                                    'address_id' => $defaultAddress->id,
                                    'address_type' => $defaultAddress->address_type
                                ]);
                            } else {
                                // Debug: Log if no addresses found
                                Log::info('getAccountMasterDetailsTraitField: No addresses found for AccountMaster', [
                                    'account_master_id' => $state
                                ]);
                                $set('billing_address_id', null); // Clear address if no addresses available
                            }

                            $contactId = $get('contact_detail_id');

                            if (!$contactId) {
                                $contactId = $accountMaster->contactDetails()->first()?->id;
                                if ($contactId) {
                                    $set('contact_detail_id', $contactId); // Set the first contact in the form
                                }
                            }

                            if ($contactId) {
                                    try {
                                        $accountMaster->contactDetails()->syncWithoutDetaching([$contactId]);
                                    } catch (Exception $e) {
                                        Notification::make()
                                            ->title('Error syncing contact')
                                            ->danger()
                                            ->send();
                                    }
                                }
                                $set('show_account_master_info', $state);
                                } else {
                                    // Clear fields if no account_master_id is selected
                                    $set('contact_detail_id', null);
                                    $set('show_account_master_info', null);
                                    $set('billing_address_id', null); // Clear address
                                }
                            })
                            ->afterStateHydrated(function (callable $set, $state, callable $get, $component) {
                                if ($state) {
                                $set('show_account_master_info', $state);
                                $contactId = $get('contact_detail_id');
                                if ($contactId) {
                                    $accountMaster = AccountMaster::find($state);
                                    if ($accountMaster && $contactId) {
                                        $accountMaster->contactDetails()->syncWithoutDetaching([$contactId]);
                                    }
                                }

                                // Auto-select address during hydration
                                $accountMaster = AccountMaster::with('addresses')->find($state);
                                if ($accountMaster && $accountMaster->addresses->isNotEmpty()) {
                                    $defaultAddress = $accountMaster->addresses->where('address_type', 'Billing')->first()
                                        ?? $accountMaster->addresses->first();
                                    $set('billing_address_id', $defaultAddress->id);
                                }
                                    } else {
                                        // Set the account_master_id from the model if available
                                        $existingAccountMasterId = $component->getModelInstance()->account_master_id;
                                        if ($existingAccountMasterId) {
                                            $set('account_master_id', $existingAccountMasterId);
                                            $set('show_account_master_info', $existingAccountMasterId);

                                            // Auto-select address for existing account master
                                            $accountMaster = AccountMaster::with('addresses')->find($existingAccountMasterId);
                                            if ($accountMaster && $accountMaster->addresses->isNotEmpty()) {
                                                $defaultAddress = $accountMaster->addresses->where('address_type', 'Billing')->first()
                                                    ?? $accountMaster->addresses->first();
                                                $set('billing_address_id', $defaultAddress->id);
                                            }
                                        }
                                    }
                                })
                            ->getOptionLabelUsing(fn ($value) =>
                                AccountMaster::find($value)?->name ?? 'Unknown Account Master'
                            ),

                        Placeholder::make('Account Details')
                            ->hidden(fn (callable $get) => !$get('account_master_id'))
                            ->label('Business Details')
                            ->content(function (callable $get) {
                                $accountMaster = AccountMaster::find($get('account_master_id'));

                                $accountDetails = $accountMaster
                                    ? "🏢 {$accountMaster->name}
                                    📧 {$accountMaster->email}
                                    🌐 {$accountMaster->website}"
                                    : 'No account details available.';

                                return "{$accountDetails}";
                            }),

                    ])->columnSpanFull(),
        ];
    }
}
