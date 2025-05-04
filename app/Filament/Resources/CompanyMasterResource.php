<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyMasterResource\Pages;
use App\Filament\Resources\CompanyMasterResource\RelationManagers;
use App\Models\CompanyMaster;
use App\Models\Category;
use App\Models\NumberSeries;
use App\Models\TypeMaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Select, BelongsToManyCheckboxList, BelongsToManyMultiSelect, HasManyRepeater};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, SelectColumn};
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\Support\Contracts\GlobalSearchProvider;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;


use Filament\Facades\Filament;

class CompanyMasterResource extends Resource
{
    protected static ?string $model = CompanyMaster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Masters';
    protected static ?int $navigationSort = 200;
    protected static ?string $navigationLabel = 'Comapany Master';


    public static function getGloballySearchableAttributes(): array
    {
        return ['company_code']; // Define searchable fields
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name ?? 'No Lead Code';
    }

    public static function getGlobalSearchResults(string $search): Collection
    {
        $results = collect();

        // Check if search matches the module name "Company"
        if (strtolower($search) === 'company' || strtolower($search) === 'companies') {
            $results->push(new GlobalSearchResult(
                title: 'View All Companies',
                url: route('filament.admin.resources.company-masters.index'),
            ));
        }

        return CompanyMaster::query()
            ->where('company_code', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn ($company_master) => new GlobalSearchResult(
                title: $company_master->company_code ?? 'Unknown company master', // ✅ Ensure title is a string
                url: route('filament.admin.resources.company-masters.edit', $company_master->id), // ✅ Correct edit link
            ));
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name', function ($query, callable $get) {
                        if ($contactId = $get('contact_detail_id')) {
                            $contact = \App\Models\ContactDetail::with('company')->find($contactId);
                            return $query->where('id', $contact?->company_id);
                        }
                        return $query;
                    })
                    ->searchable()
                    ->nullable()
                    ->live()
                    ->extraAttributes(fn (callable $get) => $get('company_id') ? ['class' => 'hide-create-button'] : [])
                    ->createOptionForm(fn (callable $get) => $get('company_id')
                        ? [
                            Forms\Components\Placeholder::make('info')
                                ->label('Info')
                                ->content('The selected contact already belongs to a company. Creating a new company is not allowed.')
                            ]
                        : [
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
                                ->maxLength(255)->nullable(),

                            Forms\Components\Textarea::make('description')
                                ->nullable()
                                ->label('Company Description'),
                        ])
                    ])
                    ->createOptionUsing(function (array $data, callable $set, callable $get) {
                        $company = \App\Models\Company::create($data);

                        if ($contactId = $get('contact_id')) {
                            \App\Models\ContactDetail::where('id', $contactId)
                                ->update(['company_id' => $company->id]);
                        }

                        $set('company_id', $company->id);
                        return $company->id;
                    })
                    ->suffixAction(
                        Action::make('editCompany')
                            ->icon('heroicon-o-pencil')
                            ->modalHeading('Edit Company')
                            ->modalSubmitActionLabel('Update Company')
                            ->form(fn (callable $get) => [
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->default(\App\Models\Company::find($get('company_id'))?->name)
                                            ->required()
                                            ->label('Company Name'),

                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->default(\App\Models\Company::find($get('company_id'))?->email)
                                            ->nullable()
                                            ->label('Company Email'),

                                        Forms\Components\TextInput::make('website')
                                            ->url()
                                            ->default(\App\Models\Company::find($get('company_id'))?->website)
                                            ->nullable()
                                            ->label('Website'),

                                        Forms\Components\Select::make('industry_type_id')
                                            ->relationship('industryType', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->default(fn () => \App\Models\Company::find($get('company_id'))?->industry_type_id),

                                        Forms\Components\TextInput::make('no_of_employees')
                                            ->default(\App\Models\Company::find($get('company_id'))?->no_of_employees)
                                            ->maxLength(255)
                                            ->label('Number of Employees'),

                                        Forms\Components\Textarea::make('description')
                                            ->default(\App\Models\Company::find($get('company_id'))?->description)
                                            ->nullable()
                                            ->label('Company Description'),
                                    ]),
                            ])
                            ->action(function (array $data, callable $get) {
                                $company = \App\Models\Company::find($get('company_id'));

                                if ($company) {
                                    $company->update([
                                        'name' => $data['name'] ?? $company->name,
                                        'email' => $data['email'] ?? $company->email,
                                        'website' => $data['website'] ?? $company->website,
                                        'industry_type_id' => $data['industry_type_id'] ?? $company->industry_type_id,
                                        'no_of_employees' => $data['no_of_employees'] ?? $company->no_of_employees,
                                        'description' => $data['description'] ?? $company->description,
                                    ]);

                                    Notification::make()
                                        ->title('Company Updated')
                                        ->success()
                                        ->send();
                                }
                            })
                            ->extraAttributes([
                                'x-on:click' => '$dispatch("open-modal", { id: "edit-company-modal", width: "max-w-7xl" })',
                            ])
                            ->requiresConfirmation()
                            ->visible(fn (callable $get) => $get('company_id'))
                    )
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $set('show_company_info', $state);

                            // Filter contacts by selected company
                            $contacts = \App\Models\ContactDetail::where('company_id', $state)
                                ->get()
                                ->mapWithKeys(fn ($contact) => [
                                    $contact->id => "{$contact->first_name} {$contact->last_name}"
                                ]);

                            // Dynamically set contact field options
                            $set('contact_detail_id_options', $contacts);
                        } else {
                            $set('contact_detail_id_options', []);
                        }
                    })
                    // ->afterStateUpdated(fn (callable $set, $state) => $set('show_company_info', $state))
                    ->afterStateHydrated(fn (callable $set, $state) => $set('show_company_info', $state))
                    ->getOptionLabelUsing(fn ($value) =>
                            \App\Models\Company::find($value)?->name ?? 'Unknown Company'
                        ),

                Forms\Components\Select::make('contact_detail_id')
                        ->label('Contact')
                        ->relationship('contactDetails', 'first_name') // ✅ Many-to-Many Relationship
                        ->preload()
                        ->multiple()
                        ->searchable()
                        ->nullable()
                        ->live()
                        ->options(fn (callable $get) =>
                            $get('company_id')
                                ? \App\Models\ContactDetail::where('company_id', $get('company_id'))
                                    ->get()
                                    ->mapWithKeys(fn ($contact) => [
                                        $contact->id => "{$contact->first_name} {$contact->last_name}"
                                    ])
                                : []
                        )
                    ->createOptionForm([
                        Forms\Components\Grid::make(3) // ✅ Three-column layout
                            ->schema([
                                Forms\Components\Hidden::make('company_id')
                                    ->default(fn (callable $get) => $get('company_id')) // ✅ Auto-set `company_id`
                                    ->dehydrated(),
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
                    ->createOptionUsing(function (array $data, callable $set) {
                        $contact = \App\Models\ContactDetail::create($data);

                        // ✅ Pass `contact_id` to Address Form
                        $set('contact_id', $contact->id);

                        return $contact->id;
                    })
                    ->afterStateUpdated(function (callable $set, $state) {
                        //
                    })
                    ->afterStateHydrated(fn (callable $set, $state) => $set('show_contact_info', $state)),

                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->required(),

                Select::make('typeable_id')
                    ->label('Type')
                    ->options(fn () => TypeMaster::where('typeable_type', CompanyMaster::class)->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                    TextInput::make('vendor_code')->maxLength(100),
                TextInput::make('company_code')
                    ->label('Company Code')
                    ->default(fn () => NumberSeries::getNextNumber(CompanyMaster::class))
                    ->readOnly()
                    ->required(),
                Select::make('address_id')
                    ->relationship('address', 'street')
                    ->required(),
                Select::make('dealer_name_id')
                    ->relationship('dealerName', 'id') // Use 'id' here for relationship
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->FullName),
                TextInput::make('commission')
                    ->numeric()
                    ->suffix('%'),

                Select::make('category_id')
                    ->label('Category')
                    ->options(fn () => Category::ofType(CompanyMaster::class)->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_id')->searchable(),
                TextColumn::make('vendor_code')->sortable(),
                TextColumn::make('company_code')->sortable(),
                TextColumn::make('category_type')
                    ->badge()
                    ->colors([
                        'success' => 'Item',
                        'warning' => 'Expense',
                        'info' => 'Travel',
                    ]),
                TextColumn::make('commission')->suffix('%')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanyMasters::route('/'),
            'create' => Pages\CreateCompanyMaster::route('/create'),
            'edit' => Pages\EditCompanyMaster::route('/{record}/edit'),
        ];
    }
}
