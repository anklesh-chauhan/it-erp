<?php

namespace App\Filament\Resources\CompanyMasters;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use App\Models\ContactDetail;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use App\Models\Company;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use App\Models\Designation;
use App\Models\Department;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CompanyMasters\Pages\ListCompanyMasters;
use App\Filament\Resources\CompanyMasters\Pages\CreateCompanyMaster;
use App\Filament\Resources\CompanyMasters\Pages\EditCompanyMaster;
use App\Filament\Resources\CompanyMasterResource\Pages;
use App\Filament\Resources\CompanyMasterResource\RelationManagers;
use App\Models\CompanyMaster;
use App\Models\Category;
use App\Models\NumberSeries;
use App\Models\TypeMaster;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
    use HasSafeGlobalSearch;
    protected static ?string $model = CompanyMaster::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Masters';
    protected static ?int $navigationSort = 202;
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


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name', function ($query, callable $get) {
                        if ($contactId = $get('contact_detail_id')) {
                            $contact = ContactDetail::with('company')->find($contactId);
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
                            Placeholder::make('info')
                                ->label('Info')
                                ->content('The selected contact already belongs to a company. Creating a new company is not allowed.')
                            ]
                        : [
                        Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->label('Company Name'),

                            TextInput::make('email')
                                ->email()
                                ->nullable()
                                ->label('Company Email'),

                            TextInput::make('website')
                                ->url()
                                ->nullable()
                                ->label('Website'),

                            Select::make('industry_type_id')
                                ->relationship('industryType', 'name')
                                ->searchable()
                                ->nullable()
                                ->label('Industry Type')
                                ->preload(),

                            TextInput::make('no_of_employees')
                                ->maxLength(255)->nullable(),

                            Textarea::make('description')
                                ->nullable()
                                ->label('Company Description'),
                        ])
                    ])
                    ->createOptionUsing(function (array $data, callable $set, callable $get) {
                        $company = Company::create($data);

                        if ($contactId = $get('contact_id')) {
                            ContactDetail::where('id', $contactId)
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
                            ->schema(fn (callable $get) => [
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->default(Company::find($get('company_id'))?->name)
                                            ->required()
                                            ->label('Company Name'),

                                        TextInput::make('email')
                                            ->email()
                                            ->default(Company::find($get('company_id'))?->email)
                                            ->nullable()
                                            ->label('Company Email'),

                                        TextInput::make('website')
                                            ->url()
                                            ->default(Company::find($get('company_id'))?->website)
                                            ->nullable()
                                            ->label('Website'),

                                        Select::make('industry_type_id')
                                            ->relationship('industryType', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->default(fn () => Company::find($get('company_id'))?->industry_type_id),

                                        TextInput::make('no_of_employees')
                                            ->default(Company::find($get('company_id'))?->no_of_employees)
                                            ->maxLength(255)
                                            ->label('Number of Employees'),

                                        Textarea::make('description')
                                            ->default(Company::find($get('company_id'))?->description)
                                            ->nullable()
                                            ->label('Company Description'),
                                    ]),
                            ])
                            ->action(function (array $data, callable $get) {
                                $company = Company::find($get('company_id'));

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
                            $contacts = ContactDetail::where('company_id', $state)
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
                            Company::find($value)?->name ?? 'Unknown Company'
                        ),

                Select::make('contact_detail_id')
                        ->label('Contact')
                        ->relationship('contactDetails', 'first_name') // ✅ Many-to-Many Relationship
                        ->preload()
                        ->multiple()
                        ->searchable()
                        ->nullable()
                        ->live()
                        ->options(fn (callable $get) =>
                            $get('company_id')
                                ? ContactDetail::where('company_id', $get('company_id'))
                                    ->get()
                                    ->mapWithKeys(fn ($contact) => [
                                        $contact->id => "{$contact->first_name} {$contact->last_name}"
                                    ])
                                : []
                        )
                    ->createOptionForm([
                        Grid::make(3) // ✅ Three-column layout
                            ->schema([
                                Hidden::make('company_id')
                                    ->default(fn (callable $get) => $get('company_id')) // ✅ Auto-set `company_id`
                                    ->dehydrated(),
                                Select::make('salutation')
                                ->label('Salutation')
                                ->options([
                                    'Mr.' => 'Mr.',
                                    'Mrs.' => 'Mrs.',
                                    'Ms.' => 'Ms.',
                                    'Dr.' => 'Dr.',
                                    'Prof.' => 'Prof.',
                                ])->nullable(),
                                TextInput::make('first_name')->required(),
                                TextInput::make('last_name')->nullable(),
                            ]),
                            Grid::make(3) // ✅ Three-column layout
                                ->schema([
                                    TextInput::make('email')
                                        ->email()
                                        ->required(),

                                    TextInput::make('mobile_number')
                                        ->tel()
                                        ->required()
                                        ->label('Primary Phone')
                                        ->reactive() // ✅ Enables live updates
                                        ->debounce(1000)
                                        ->afterStateUpdated(fn (callable $set, $state) => $set('whatsapp_number', $state)),

                                    TextInput::make('alternate_phone')
                                        ->tel()
                                        ->label('Alternate Phone'),

                                    ]),
                            Grid::make(3) // ✅ Three-column layout
                                ->schema([
                                    Select::make('designation_id')
                                        ->relationship('designation', 'name')
                                        ->searchable()
                                        ->nullable()
                                        ->label('Designation')
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->label('New Designation')
                                        ])
                                        ->createOptionUsing(function (array $data) {
                                            return Designation::create($data)->id;  // ✅ Create and return ID
                                        })->preload(),

                                    Select::make('department_id')
                                        ->relationship('department', 'name')
                                        ->searchable()
                                        ->nullable()
                                        ->label('Department')
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->label('New Department')
                                        ])
                                        ->createOptionUsing(function (array $data) {
                                            return Department::create($data)->id;  // ✅ Create and return ID
                                        })->preload(),
                                    DatePicker::make('birthday')
                                        ->nullable()
                                        ->displayFormat('d M Y')
                                        ->native(false)
                                        ->label('Birthday'),

                                    ]),
                                    Grid::make(4) // ✅ Three-column layout
                                    ->schema([
                                        // ✅ Social Media
                                        TextInput::make('linkedin')->url()->label('LinkedIn'),
                                        TextInput::make('facebook')->url()->label('Facebook'),
                                        TextInput::make('twitter')->url()->label('Twitter'),
                                        TextInput::make('website')->url()->label('Website'),
                                    ]),
                    ])
                    ->createOptionUsing(function (array $data, callable $set) {
                        $contact = ContactDetail::create($data);

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
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    
                        BulkApprovalAction::make(),

DeleteBulkAction::make(),
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
            'index' => ListCompanyMasters::route('/'),
            'create' => CreateCompanyMaster::route('/create'),
            'edit' => EditCompanyMaster::route('/{record}/edit'),
        ];
    }
}
