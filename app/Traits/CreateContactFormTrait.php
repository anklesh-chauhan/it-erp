<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\ContactDetail;
use App\Models\CityPinCode;
use App\Models\Company;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

trait CreateContactFormTrait
{
    /**
     * Get common form fields for SalesDocument.
     *
     * @return array
     */
    public static function getCreateContactFormTraitFields(): array
    {
        return [
            Forms\Components\Grid::make(3) // ✅ Three-column layout
                    ->schema([
                        // ✅ Salutation
                        Forms\Components\Select::make('salutation')
                            ->options([
                                'Mr.'   => 'Mr.',
                                'Mrs.'  => 'Mrs.',
                                'Ms.'   => 'Ms.',
                                'Dr.'   => 'Dr.',
                                'Prof.' => 'Prof.',
                                'Er.'   => 'Er.',
                                'Other' => 'Other',
                            ])
                            ->nullable()
                            ->columnSpan(1),

                        // ✅ Contact Information
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->label('First Name')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->columnSpan(1),
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


                Section::make('Additional Information')
                    ->description('Optional')
                    ->collapsed(true)
                    ->schema([
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

                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->nullable()
                                    ->reactive() // ✅ Enables dynamic updates
                                    ->afterStateHydrated(function (callable $set, callable $get, $state) {
                                        // ✅ Ensure `company_id` persists after new company creation
                                        if ($state) {
                                            $set('addresses', collect($get('addresses') ?? [])->map(function ($address) use ($state) {
                                                $address['company_id'] = $state;
                                                $address['address_type'] = $state ? 'Company' : ($address['address_type'] ?? 'Other');
                                                return $address;
                                            })->toArray());
                                        }
                                    })
                                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                        if ($state) {
                                            $company = \App\Models\Company::with('addresses')->find($state);

                                            if ($company && $company->addresses->isNotEmpty()) {
                                                $companyAddress = $company->addresses->first();

                                                // ✅ Auto-fill company address with `company_id`
                                                $set('addresses', [
                                                    [
                                                        'street'       => $companyAddress->street,
                                                        'area_town'    => $companyAddress->area_town,
                                                        'pin_code'     => $companyAddress->pin_code,
                                                        'city_id'      => $companyAddress->city_id,
                                                        'state_id'     => $companyAddress->state_id,
                                                        'country_id'   => $companyAddress->country_id,
                                                        'address_type' => 'Company',
                                                        'company_id'   => $company->id, // ✅ Inject company_id directly
                                                    ],
                                                ]);
                                            }
                                        }
                                    })
                                    ->label('Company (Optional)')
                                    ->createOptionForm([
                                        Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                                ->label('Company Name'),

                                            Forms\Components\TextInput::make('email')
                                                ->email()
                                                ->nullable()
                                                ->label('Company Email'),

                                            Forms\Components\TextInput::make('website')
                                                ->url()
                                                ->nullable()
                                                ->label('Website'),

                                            Forms\Components\Select::make('industry_type_id')
                                                ->relationship('industryType', 'name')
                                                ->searchable()
                                                ->nullable()
                                                ->label('Industry Type')
                                                ->preload(),

                                            Forms\Components\TextInput::make('no_of_employees')
                                                ->maxLength(255),

                                            Forms\Components\Textarea::make('description')
                                                ->nullable()
                                                ->label('Company Description'),
                                        ])
                                    ])
                                    ->createOptionUsing(function (array $data, callable $set, callable $get) {
                                        $company = \App\Models\Company::create($data);

                                        // ✅ Force `.afterStateUpdated()` to run and apply logic
                                        $set('company_id', $company->id);

                                        // ✅ Inject newly created `company_id` into addresses array
                                        $set('addresses', collect($get('addresses') ?? [])->map(function ($address) use ($company) {
                                            $address['company_id'] = $company->id;
                                            $address['address_type'] = 'Company';
                                            return $address;
                                        })->toArray());

                                        return $company->id; // ✅ Return the new company ID
                                    })
                                    ->preload(), // ✅ Preload data for faster search


                                Forms\Components\TextInput::make('whatsapp_number')
                                    ->tel()
                                    ->label('WhatsApp Number')
                                    ->placeholder('Same as phone number unless changed'),

                        Forms\Components\Grid::make(4) // ✅ Three-column layout
                            ->schema([
                                // ✅ Social Media
                                Forms\Components\TextInput::make('linkedin')->url()->label('LinkedIn'),
                                Forms\Components\TextInput::make('facebook')->url()->label('Facebook'),
                                Forms\Components\TextInput::make('twitter')->url()->label('Twitter'),
                                Forms\Components\TextInput::make('website')->url()->label('Website'),
                            ]),
                        Forms\Components\Grid::make(1) // ✅ Three-column layout
                            ->schema([
                        // ✅ Notes
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->label('Additional Notes'),
                        ]),
            ]),
        ];

    }
}
