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
                            ->relationship('contactDetail', 'id')
                            ->options(function (callable $get) {
                                $companyId = $get('company_id');
                                $accountMasterId = $get('account_master_id');

                                $query = \App\Models\ContactDetail::query();

                                // Filter by company if selected
                                if ($companyId) {
                                    $query->where('company_id', $companyId);
                                }

                                // If account master is selected, get related contacts
                                if ($accountMasterId) {
                                    $query->whereHas('accountMaster', fn ($q) =>
                                        $q->where('account_masters.id', $accountMasterId)
                                    );
                                }

                                return $query->get()
                                    ->mapWithKeys(fn ($contact) => [
                                        $contact->id => "{$contact->full_name} — " . ($contact->company?->name ?? 'No Company'),
                                    ])
                                    ->toArray();
                            })
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $query = \App\Models\ContactDetail::query();

                                // Filter by company_id if provided
                                if ($companyId = $get('company_id')) {
                                    $query->where('company_id', $companyId);
                                }

                                // Apply search filters
                                $query->where(function ($query) use ($search) {
                                    $query->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhereHas('company', fn ($query) =>
                                            $query->where('name', 'like', "%{$search}%")
                                        );
                                });


                                return $query->get()
                                    ->mapWithKeys(fn ($contact) => [
                                        $contact->id => "{$contact->full_name} — " .
                                        ($contact->company?->name ?? 'No Company')
                                    ]);
                            })
                            ->getOptionLabelUsing(fn ($value) =>
                                ($contact = \App\Models\ContactDetail::find($value))
                                    ? "{$contact->full_name} — " .
                                    ($contact->company?->name ?? 'No Company')
                                    : 'Unknown Contact'
                            )
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->live()
                            ->createOptionForm([
                                Forms\Components\Grid::make(3) // ✅ Three-column layout
                                    ->schema([
                                        Forms\Components\Select::make('salutation')
                                        ->label('Salutation')
                                        ->options([
                                            'Mr.' => 'Mr.',
                                            'Mrs.' => 'Mrs.',
                                            'Ms.' => 'Ms.',
                                            'Dr.' => 'Dr.',
                                            'Prof.' => 'Prof.',
                                        ])->nullable(),
                                        Forms\Components\TextInput::make('first_name')->required(),
                                        Forms\Components\TextInput::make('last_name')->nullable(),
                                    ]),
                                    Forms\Components\Grid::make(3) // ✅ Three-column layout
                                        ->schema([
                                            Forms\Components\TextInput::make('email')
                                                ->email()
                                                ->required(),

                                            Forms\Components\TextInput::make('mobile_number')
                                                ->tel()
                                                ->required()
                                                ->label('Primary Phone')
                                                ->reactive() // ✅ Enables live updates
                                                ->debounce(1000)
                                                ->afterStateUpdated(fn (callable $set, $state) => $set('whatsapp_number', $state)),

                                            Forms\Components\TextInput::make('alternate_phone')
                                                ->tel()
                                                ->label('Alternate Phone'),

                                            ]),
                                    Forms\Components\Grid::make(3) // ✅ Three-column layout
                                        ->schema([
                                            Forms\Components\Select::make('designation_id')
                                                ->relationship('designation', 'name')
                                                ->searchable()
                                                ->nullable()
                                                ->label('Designation')
                                                ->createOptionForm([
                                                    Forms\Components\TextInput::make('name')
                                                        ->required()
                                                        ->label('New Designation')
                                                ])
                                                ->createOptionUsing(function (array $data) {
                                                    return \App\Models\Designation::create($data)->id;  // ✅ Create and return ID
                                                })->preload(),

                                            Forms\Components\Select::make('department_id')
                                                ->relationship('department', 'name')
                                                ->searchable()
                                                ->nullable()
                                                ->label('Department')
                                                ->createOptionForm([
                                                    Forms\Components\TextInput::make('name')
                                                        ->required()
                                                        ->label('New Department')
                                                ])
                                                ->createOptionUsing(function (array $data) {
                                                    return \App\Models\Department::create($data)->id;  // ✅ Create and return ID
                                                })->preload(),
                                            Forms\Components\DatePicker::make('birthday')
                                                ->nullable()
                                                ->displayFormat('d M Y')
                                                ->native(false)
                                                ->label('Birthday'),

                                            ]),
                                            Forms\Components\Grid::make(4) // ✅ Three-column layout
                                            ->schema([
                                                // ✅ Social Media
                                                Forms\Components\TextInput::make('linkedin')->url()->label('LinkedIn'),
                                                Forms\Components\TextInput::make('facebook')->url()->label('Facebook'),
                                                Forms\Components\TextInput::make('twitter')->url()->label('Twitter'),
                                                Forms\Components\TextInput::make('website')->url()->label('Website'),
                                            ]),
                            ])
                            ->createOptionUsing(function (array $data, callable $set, callable $get) {
                                // Retrieve the company_id using $get
                                if ($companyId = $get('company_id')) {
                                    $data['company_id'] = $companyId;
                                }

                                // Create the ContactDetail record
                                $contact = \App\Models\ContactDetail::create($data);

                                // Pass `contact_id` to Address Form
                                $set('contact_id', $contact->id);

                                return $contact->id;
                            })
                            ->createOptionAction(fn (Forms\Components\Actions\Action $action) =>
                                    $action->hidden(fn (callable $get) => $get('contact_detail_id') !== null) // ✅ Hide "Create" button when a contact is selected
                                )
                            ->suffixAction(
                                Action::make('editContact')
                                    ->icon('heroicon-o-pencil')
                                    ->modalHeading('Edit Contact')
                                    ->modalSubmitActionLabel('Update Contact')
                                    ->form(fn (callable $get) => [
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('first_name')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->first_name)
                                                    ->required(),

                                                Forms\Components\TextInput::make('last_name')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->last_name)
                                                    ->nullable(),
                                            ]),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('email')
                                                    ->email()
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->email)
                                                    ->required(),

                                                Forms\Components\TextInput::make('mobile_number')
                                                    ->tel()
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->mobile_number)
                                                    ->required()
                                                    ->label('Primary Phone')
                                                    ->reactive()
                                                    ->debounce(1000)
                                                    ->afterStateUpdated(fn (callable $set, $state) => $set('whatsapp_number', $state)),
                                            ]),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('designation_id')
                                                    ->relationship('designation', 'name')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->designation_id)
                                                    ->searchable()
                                                    ->nullable()
                                                    ->label('Designation')
                                                    ->createOptionForm([
                                                        Forms\Components\TextInput::make('name')
                                                            ->required()
                                                            ->label('New Designation')
                                                    ])
                                                    ->createOptionUsing(function (array $data) {
                                                        return \App\Models\Designation::create($data)->id;
                                                    })->preload(),

                                                Forms\Components\Select::make('department_id')
                                                    ->relationship('department', 'name')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->department_id)
                                                    ->searchable()
                                                    ->nullable()
                                                    ->label('Department')
                                                    ->createOptionForm([
                                                        Forms\Components\TextInput::make('name')
                                                            ->required()
                                                            ->label('New Department')
                                                    ])
                                                    ->createOptionUsing(function (array $data) {
                                                        return \App\Models\Department::create($data)->id;
                                                    })->preload(),
                                            ]),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('linkedin')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->linkedin)
                                                    ->url()->label('LinkedIn'),

                                                Forms\Components\TextInput::make('facebook')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->facebook)
                                                    ->url()->label('Facebook'),

                                                Forms\Components\TextInput::make('twitter')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->twitter)
                                                    ->url()->label('Twitter'),

                                                Forms\Components\TextInput::make('website')
                                                    ->default(\App\Models\ContactDetail::find($get('contact_detail_id'))?->website)
                                                    ->url()->label('Website'),
                                            ]),
                                    ])
                                    ->action(function (array $data, callable $get) {
                                        $contact = \App\Models\ContactDetail::find($get('contact_detail_id'));
                                        if ($contact) {
                                            $contact->update($data);
                                            Notification::make()
                                                ->title('Contact Updated')
                                                ->success()
                                                ->send();
                                        }
                                    })
                                    ->requiresConfirmation()
                                    ->visible(fn (callable $get) => $get('contact_detail_id'))
                            )
                            // ✅ Display Contact Info on Selection
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                if ($contact = \App\Models\ContactDetail::with(['company', 'addresses'])->find($state)) {
                                    $set('show_contact_info', $state);
                                    $set('contact_id', $state);
                                    $set('company_id', $contact->company_id);
                                    $set('address_id', $contact->addresses->first()?->id);
                                }

                                // If contact not selected but account_master_id exists, select first related contact
                                if (!$state && $accountMasterId = $get('account_master_id')) {
                                    $firstContact = \App\Models\AccountMaster::find($accountMasterId)?->contactDetails()->first();
                                    if ($firstContact) {
                                        $set('contact_detail_id', $firstContact->id);
                                        $set('show_contact_info', $firstContact->id);
                                        $set('contact_id', $firstContact->id);
                                        $set('company_id', $firstContact->company_id);
                                        $set('address_id', $firstContact->addresses->first()?->id);
                                    }
                                }
                            })
                            ->afterStateHydrated(function (callable $set, callable $get, $state) {
                                $set('show_contact_info', $state);

                                // Same logic on hydration if contact is missing but account master is present
                                if (!$state && $accountMasterId = $get('account_master_id')) {
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
                            ->hidden(fn (callable $get) => !$get('show_contact_info') && !$get('company_id'))
                            ->content(function (callable $get) {
                                $contact = \App\Models\ContactDetail::find($get('contact_detail_id'));
                                $company = $contact?->company ?? \App\Models\Company::find($get('company_id'));
                                $address = $contact?->addresses->first() ?? \App\Models\Address::find($get('address_id'));
                                // Extract clean city, state, and country names
                                $city = $address?->city?->name ?? $address?->city;
                                $state = $address?->state?->name ?? $address?->state;
                                $country = $address?->country?->name ?? $address?->country;

                                $contactDetails = $contact
                                    ? "👤 {$contact->first_name} {$contact->last_name}
                                    📧 {$contact->email}
                                    📱 {$contact->mobile_number}"
                                    : 'No contact details selected.';

                                return "{$contactDetails}";
                            }),
                    ]),

        ];
    }
}
