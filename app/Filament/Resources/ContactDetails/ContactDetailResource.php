<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactDetailResource\Pages;
use App\Models\ContactDetail;
use App\Models\CityPinCode;
use App\Models\Company;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;


class ContactDetailResource extends Resource
{

    protected static ?string $model = ContactDetail::class;

    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Contacts';
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

                Forms\Components\Grid::make(1) // ✅ Three-column layout
                    ->schema([

                    // 🔄 Add Address Repeater
                    Forms\Components\Repeater::make('addresses')
                    ->relationship('addresses')
                    ->schema([
                        Forms\Components\Grid::make(3) // ✅ Three-column layout
                        ->schema([

                            Forms\Components\Hidden::make('company_id')
                                ->default(fn (callable $get) => $get('company_id')) // ✅ Auto-set when creating new records
                                ->dehydrated(),

                            Forms\Components\Select::make('address_type')
                                ->options([
                                    'Company' => 'Company',
                                    'Home' => 'Home',
                                    'Office' => 'Office',
                                    'Other' => 'Other',
                                ])
                                ->required()
                                ->label('Address Type'),

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
                        ]),
                    ])
                    ->collapsible() // Optional for better UI
                    ->orderColumn() // Enables drag & drop sorting
                    ->addActionLabel('Add Address') // ✅ Custom add button text
                    ->default(function (callable $get) {
                        return [['company_id' => $get('company_id')]]; // ✅ Ensures `company_id` is included by default
                    }),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('salutation')
                    ->sortable(),

                Tables\Columns\TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('birthday')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mobile_number')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable()
                    ->default('N/A'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->relationship('company', 'name')
                    ->label('Filter by Company'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactDetails::route('/'),
            'create' => Pages\CreateContactDetail::route('/create'),
            'edit' => Pages\EditContactDetail::route('/{record}/edit'),
        ];
    }
}
