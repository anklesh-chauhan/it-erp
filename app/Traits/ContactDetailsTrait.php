<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

trait ContactDetailsTrait
{
    /**
     * Get common form fields for SalesDocument.
     *
     * @return array
     */
    public static function getContactDetailsTraitField(): array
    {
        return [
            Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('contact_detail_id')
                            ->label('Contact')
                            ->options(function (callable $get) {
                                $accountMasterId = $get('account_master_id');

                                $query = \App\Models\ContactDetail::query();

                                if ($accountMasterId) {
                                    $query->whereHas('accountMasters', fn ($q) =>
                                        $q->where('account_masters.id', $accountMasterId)
                                    );
                                }

                                return $query->get()
                                    ->mapWithKeys(fn ($contact) => [
                                        $contact->id => "{$contact->full_name} — " . ($contact->accountMasters?->first()->name ?? 'No Account'),
                                    ])
                                    ->toArray();
                            })
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $accountMasterId = $get('account_master_id');
                                $query = \App\Models\ContactDetail::query();

                                if ($accountMasterId) {
                                    $query->whereHas('accountMasters', fn ($q) =>
                                        $q->where('account_masters.id', $accountMasterId)
                                    );
                                }

                                $query->where(function ($query) use ($search) {
                                    $query->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhereHas('accountMasters', fn ($q) =>
                                            $q->where('name', 'like', "%{$search}%")
                                        );
                                });

                                return $query->get()
                                    ->mapWithKeys(fn ($contact) => [
                                        $contact->id => "{$contact->full_name} — " . ($contact->accountMasters?->first()->name ?? 'No Account'),
                                    ]);
                            })
                            ->getOptionLabelUsing(fn ($value) =>
                                ($contact = \App\Models\ContactDetail::find($value))
                                    ? "{$contact->full_name} — " . ($contact->accountMasters?->first()->name ?? 'No Account')
                                    : 'Unknown Contact'
                            )
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->live()
                            ->createOptionForm([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('salutation')->label('Salutation')->options([
                                        'Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Ms.' => 'Ms.', 'Dr.' => 'Dr.', 'Prof.' => 'Prof.',
                                    ])->nullable(),
                                    Forms\Components\TextInput::make('first_name')->required(),
                                    Forms\Components\TextInput::make('last_name')->nullable(),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('email')->email()->required(),
                                    Forms\Components\TextInput::make('mobile_number')->required()->label('Primary Phone')
                                        ->mask('+919999999999')->live(onBlur: true)->debounce(1000)
                                        ->afterStateUpdated(fn (callable $set, $state) => $set('whatsapp_number', $state)),
                                    Forms\Components\TextInput::make('alternate_phone')->mask('+919999999999')->nullable()->label('Alternate Phone'),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('designation_id')
                                        ->relationship('designation', 'name')
                                        ->model(\App\Models\ContactDetail::class)
                                        ->searchable()
                                        ->nullable()
                                        ->label('Designation')
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                                ->label('New Designation'),
                                        ])
                                        ->createOptionUsing(fn (array $data) => \App\Models\Designation::create($data)->id)
                                        ->preload()
                                        ->default(fn (callable $get) => \App\Models\ContactDetail::find($get('contact_detail_id'))?->designation_id),

                                    Forms\Components\Select::make('department_id')
                                        ->relationship('department', 'name')
                                        ->model(\App\Models\ContactDetail::class)
                                        ->searchable()
                                        ->nullable()
                                        ->label('Department')
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                                ->label('New Department'),
                                        ])
                                        ->createOptionUsing(fn (array $data) => \App\Models\Department::create($data)->id)
                                        ->preload()
                                        ->default(fn (callable $get) => \App\Models\ContactDetail::find($get('contact_detail_id'))?->department_id),

                                    Forms\Components\DatePicker::make('birthday')->nullable()->displayFormat('d M Y')->native(false)->label('Birthday'),
                                ]),
                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\TextInput::make('linkedin')->url()->label('LinkedIn'),
                                    Forms\Components\TextInput::make('facebook')->url()->label('Facebook'),
                                    Forms\Components\TextInput::make('twitter')->url()->label('Twitter'),
                                    Forms\Components\TextInput::make('website')->url()->label('Website'),
                                ]),
                            ])
                            ->createOptionUsing(function (array $data, callable $set, callable $get) {
                                $contact = \App\Models\ContactDetail::create($data);

                                // Attach to account master if account_master_id exists
                                if ($accountMasterId = $get('account_master_id')) {
                                    $contact->accountMasters()->syncWithoutDetaching([$accountMasterId]);
                                }

                                $set('contact_id', $contact->id);
                                return $contact->id;
                            })
                            ->createOptionAction(fn (Forms\Components\Actions\Action $action) =>
                                $action->hidden(fn (callable $get) => $get('contact_detail_id') !== null)
                            )
                            ->suffixAction(
                                Action::make('editContact')
                                    ->icon('heroicon-o-pencil')
                                    ->modalHeading('Edit Contact')
                                    ->modalSubmitActionLabel('Update Contact')
                                    ->form(fn (callable $get) => [
                                        Forms\Components\Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('first_name')
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->first_name)
                                                ->required(),
                                            Forms\Components\TextInput::make('last_name')
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->last_name)
                                                ->nullable(),
                                        ]),
                                        Forms\Components\Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('email')->email()
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->email)->required(),
                                            Forms\Components\TextInput::make('mobile_number')->tel()
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->mobile_number)
                                                ->required()->label('Primary Phone')->reactive()->debounce(1000)
                                                ->afterStateUpdated(fn (callable $set, $state) => $set('whatsapp_number', $state)),
                                        ]),
                                        Forms\Components\Grid::make(2)->schema([
                                            Forms\Components\Select::make('designation_id')
                                                ->relationship('designation', 'name')
                                                ->model(\App\Models\ContactDetail::class)
                                                ->searchable()
                                                ->nullable()
                                                ->label('Designation')
                                                ->createOptionForm([
                                                    Forms\Components\TextInput::make('name')
                                                        ->required()
                                                        ->label('New Designation'),
                                                ])
                                                ->createOptionUsing(fn (array $data) => \App\Models\Designation::create($data)->id)
                                                ->preload()
                                                ->default(fn (callable $get) => \App\Models\ContactDetail::find($get('contact_detail_id'))?->designation_id),

                                            Forms\Components\Select::make('department_id')
                                                ->relationship('department', 'name')
                                                ->model(\App\Models\ContactDetail::class)
                                                ->searchable()
                                                ->nullable()
                                                ->label('Department')
                                                ->createOptionForm([
                                                    Forms\Components\TextInput::make('name')
                                                        ->required()
                                                        ->label('New Department'),
                                                ])
                                                ->createOptionUsing(fn (array $data) => \App\Models\Department::create($data)->id)
                                                ->preload()
                                                ->default(fn (callable $get) => \App\Models\ContactDetail::find($get('contact_detail_id'))?->department_id),
                                        ]),
                                        Forms\Components\Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('linkedin')
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->linkedin)->url()->label('LinkedIn'),
                                            Forms\Components\TextInput::make('facebook')
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->facebook)->url()->label('Facebook'),
                                            Forms\Components\TextInput::make('twitter')
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->twitter)->url()->label('Twitter'),
                                            Forms\Components\TextInput::make('website')
                                                ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->website)->url()->label('Website'),
                                        ]),
                                    ])
                                    ->action(function (array $data, callable $get) {
                                        $contact = \App\Models\ContactDetail::find($get('contact_detail_id'));
                                        if ($contact) {
                                            $contact->update($data);
                                            Notification::make()->title('Contact Updated')->success()->send();
                                        }
                                    })
                                    ->requiresConfirmation()
                                    ->visible(fn (callable $get) => $get('contact_detail_id'))
                            )
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                if ($state && $contact = \App\Models\ContactDetail::with('accountMasters')->find($state)) {
                                    $set('show_contact_info', $state);
                                    $set('contact_id', $state);

                                    // Attach to account master if account_master_id exists
                                    if ($accountMasterId = $get('account_master_id')) {
                                        $contact->accountMasters()->syncWithoutDetaching([$accountMasterId]);
                                    }

                                    // Set the first related account master in the account_master_id field
                                    $firstAccount = $contact->accountMasters()->first();
                                    $set('account_master_id', $firstAccount ? $firstAccount->id : null);

                                    // Set the first related address
                                    $set('address_id', $contact->addresses->first()?->id);
                                } else {
                                    // Clear account_master_id and other fields if no contact is selected
                                    $set('account_master_id', null);
                                    $set('show_contact_info', null);
                                    $set('contact_id', null);
                                    $set('address_id', null);
                                
                                }
                            })
                            ->afterStateHydrated(function (callable $set, callable $get, $state, $component) {
                                // Set contact-related fields
                                $set('show_contact_info', $state);
                                $set('contact_id', $state);

                                // Load the contact and its account masters
                                if ($state && $contact = \App\Models\ContactDetail::with('accountMasters')->find($state)) {
                                    // Get the existing account_master_id from the form or model
                                    $existingAccountMasterId = $get('account_master_id') ?? $component->getModelInstance()->account_master_id;

                                    // Set the account_master_id to the existing value or the first related account master
                                    if ($existingAccountMasterId && $contact->accountMasters()->where('account_masters.id', $existingAccountMasterId)->exists()) {
                                        $set('account_master_id', $existingAccountMasterId);
                                    } else {
                                        $firstAccount = $contact->accountMasters()->first();
                                        $set('account_master_id', $firstAccount ? $firstAccount->id : null);
                                    }

                                    // Set the first related address
                                    $set('address_id', $contact->addresses->first()?->id);
                                } elseif ($accountMasterId = $get('account_master_id')) {
                                    // If no contact but an account master exists, try to set a contact
                                    $firstContact = \App\Models\AccountMaster::find($accountMasterId)?->contactDetails()->first();
                                    if ($firstContact) {
                                        $set('contact_detail_id', $firstContact->id);
                                        $set('show_contact_info', $firstContact->id);
                                        $set('contact_id', $firstContact->id);
                                        $set('company_id', $firstContact->company_id);
                                        $set('address_id', $firstContact->addresses->first()?->id);
                                    }
                                }
                            }),

                        Forms\Components\Placeholder::make('Contact Information')
                            ->hidden(fn (callable $get) => !$get('show_contact_info'))
                            ->content(function (callable $get) {
                                $contact = \App\Models\ContactDetail::find($get('contact_detail_id'));
                                $account = $contact?->accountMasters->first();
                                $address = $contact?->addresses->first();
                                return $contact
                                    ? "👤 {$contact->first_name} {$contact->last_name}
                        📧 {$contact->email}
                        📱 {$contact->mobile_number}
                        🏢 " . ($account?->name ?? 'No Account')
                                    : 'No contact selected.';
                            }),

                    ]),

        ];
    }
}
