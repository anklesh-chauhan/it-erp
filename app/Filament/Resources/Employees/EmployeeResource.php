<?php

namespace App\Filament\Resources\Employees;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Illuminate\Validation\Rules\Unique;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth; // Import Tabs
use Filament\Forms\Components\Repeater; // Import Tab
use App\Filament\Resources\AccountMasterBankDetailResource\RelationManagers\BankDetailRelationManager;

class EmployeeResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = Employee::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | \UnitEnum | null $navigationGroup = 'HR & Organization';

    protected static ?string $navigationLabel = 'Employees';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Changed from Wizard to Tabs
                Tabs::make('Employee Details')
                    ->columnSpanFull() // Ensure the tabs span the full width
                    ->tabs([
                        // Converted 'General Information' Step to a Tab
                        Tab::make('General Information')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Personal Details')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(4)
                                    ->schema([
                                        TextInput::make('employee_id')
                                            ->label('Employee ID')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(20),
                                        TextInput::make('first_name')
                                            ->required()
                                            ->maxLength(50),
                                        TextInput::make('middle_name')
                                            ->maxLength(50),
                                        TextInput::make('last_name')
                                            ->required()
                                            ->maxLength(50),
                                        Select::make('gender')
                                            ->options([
                                                'Male' => 'Male',
                                                'Female' => 'Female',
                                                'Other' => 'Other',
                                            ])
                                            ->nullable(),
                                        DatePicker::make('date_of_birth')
                                            ->native(false)
                                            ->nullable(),
                                        Select::make('marital_status')
                                            ->options([
                                                'Single' => 'Single',
                                                'Married' => 'Married',
                                                'Divorced' => 'Divorced',
                                            ])
                                            ->nullable(),
                                        Select::make('blood_group')
                                            ->options([
                                                'A+' => 'A+', 'A-' => 'A-',
                                                'B+' => 'B+', 'B-' => 'B-',
                                                'AB+' => 'AB+', 'AB-' => 'AB-',
                                                'O+' => 'O+', 'O-' => 'O-',
                                            ])
                                            ->nullable(),
                                        Select::make('country_id')
                                            ->relationship('country', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Nationality')
                                            ->nullable(),
                                    ]),
                                Section::make('Contact Information')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(4)
                                    ->schema([
                                        TextInput::make('email')
                                            ->email()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(100)
                                            ->nullable(),
                                        TextInput::make('mobile_number')
                                            ->tel()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(100)
                                            ->required(),
                                        TextInput::make('phone_number')
                                            ->tel()
                                            ->maxLength(15)
                                            ->nullable(),
                                        Textarea::make('contact_details')
                                            ->columnSpanFull() // This field correctly spans full width
                                            ->nullable(),
                                    ]),
                                Section::make('Emergency Contact')
                                    // Keeping columns(2) as there are only 2 fields
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('emergency_contact_name')
                                            ->maxLength(100)
                                            ->nullable(),
                                        TextInput::make('emergency_contact_number')
                                            ->tel()
                                            ->maxLength(15)
                                            ->nullable(),
                                    ]),
                                Section::make('Additional Details')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(3)
                                    ->schema([
                                        FileUpload::make('profile_picture')
                                            ->image()
                                            ->directory('employee-profiles')
                                            ->nullable(),
                                        Toggle::make('is_active')
                                            ->default(true),
                                        Select::make('login_id')
                                            ->relationship('user', 'email')
                                            ->searchable()
                                            ->preload()
                                            ->label('Linked User')
                                            ->nullable(),
                                        Hidden::make('updated_by_user_id')
                                            ->default(optional(Auth::user())->id),
                                    ]),
                            ]),

                        // Converted 'Employment Details' Step to a Tab
                        Tab::make('Employment Details')
                            ->icon('heroicon-o-briefcase')
                            ->disabled(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                            ->schema([
                                Section::make('Note')
                                    // This section is shown only when creating a new record
                                    ->description('This section is only available after saving the General Information.')
                                    ->visible(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                                    ->columnSpanFull()
                                    ->hiddenLabel() // Hide the default label for a cleaner look
                                    ->extraAttributes([
                                        'class' => 'p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md mb-4',
                                    ]),
                                Section::make('Current Employment')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(3)
                                    ->relationship('employmentDetail') // Binds to the employmentDetail relationship
                                    ->schema([
                                        Select::make('reporting_manager_id')
                                            ->label('Reporting Manager')
                                            ->options(fn () => Employee::all()->pluck('full_name', 'id')->toArray())
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        Select::make('department_id')
                                            ->label('Department')
                                            ->relationship('department', 'department_name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        Select::make('job_title_id')
                                            ->label('Job Title')
                                            ->relationship('jobTitle', 'title')
                                            ->preload()
                                            ->searchable()
                                            ->requiredIf('employee_id', true),
                                        Select::make('grade_id')
                                            ->label('Grade')
                                            ->relationship('grade', 'grade_name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable()
                                            ->createOptionForm([
                                                TextInput::make('grade_name')
                                                    ->label('Grade Name')
                                                    ->required()
                                                    ->maxLength(100),
                                            ]),
                                        Select::make('division_id')
                                            ->label('Division')
                                            ->relationship('division', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        Select::make('organizational_unit_id')
                                            ->label('Organizational Unit')
                                            ->relationship('organizationalUnit', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        DatePicker::make('hire_date')
                                            ->label('Hire Date')
                                            ->native(false)
                                            ->requiredIf('employee_id', true),
                                        Select::make('employment_type')
                                            ->label('Employment Type')
                                            ->options(['Permanent' => 'Permanent','Contract' => 'Contract', 'Part-Time' => 'Part-Time', 'Intern' => 'Intern', 'Temporary' => 'Temporary', 'Consultant' => 'Consultant'])
                                            ->nullable(),
                                        Select::make('employment_status')
                                            ->label('Employment Status')
                                            ->options(['Active' => 'Active', 'Inactive' => 'Inactive', 'Terminated' => 'Terminated', 'Retired' => 'Retired', 'On Leave' => 'On Leave'])
                                            ->nullable(),
                                        DatePicker::make('resign_offer_date')
                                            ->label('Resign Offer Date')
                                            ->native(false)
                                            ->nullable(),
                                        DatePicker::make('last_working_date')
                                            ->label('Last Working Date')
                                            ->native(false)
                                            ->nullable(),
                                        DatePicker::make('probation_date')
                                            ->label('Probation Date')
                                            ->native(false)
                                            ->nullable(),
                                        DatePicker::make('confirm_date')
                                            ->label('Confirmation Date')
                                            ->native(false)
                                            ->nullable(),
                                        DatePicker::make('fnf_retiring_date')
                                            ->label('FNF/Retiring Date')
                                            ->native(false)
                                            ->nullable(),
                                        DatePicker::make('last_increment_date')
                                            ->label('Last Increment Date')
                                            ->native(false)
                                            ->nullable(),
                                        Select::make('work_location_id')
                                            ->label('Work Location')
                                            ->relationship('workLocation', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),

                                        Textarea::make('remarks')
                                            ->label('Remarks')
                                            ->columnSpanFull()
                                            ->nullable(),
                                        Hidden::make('created_by')
                                            ->default(optional(Auth::user())->id),
                                        Hidden::make('updated_by')
                                            ->default(optional(Auth::user())->id),
                                    ]),
                                Section::make('Assigned Positions')
                                    ->description('Select the positions this employee holds.')
                                    ->schema([
                                        Select::make('positions')
                                            ->relationship('positions', 'name') // Use the many-to-many relationship
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->label('Select Positions')
                                            ->columnSpanFull(),
                                    ])->columns(1),
                            ]),

                        // Uncommented and converted Professional Tax to a Tab
                        Tab::make('Professional Tax')
                            ->icon('heroicon-o-banknotes')
                            ->disabled(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                            ->schema([
                                Section::make('Note')
                                    // This section is shown only when creating a new record
                                    ->description('This section is only available after saving the General Information.')
                                    ->visible(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                                    ->columnSpanFull()
                                    ->hiddenLabel() // Hide the default label for a cleaner look
                                    ->extraAttributes([
                                        'class' => 'p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md mb-4',
                                    ]),
                                Repeater::make('professionalTax')
                                    ->label('Professional Tax Entries')
                                    // Keeping columns(3) as it is already optimal for 3 fields
                                    ->columns(3)
                                    ->relationship()
                                    ->schema([
                                        Toggle::make('pt_flag')
                                            ->label('Professional Tax Applicable')
                                            ->inline(false)
                                            ->nullable(),
                                        TextInput::make('pt_no')
                                            ->label('PT Number')
                                            ->maxLength(255)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, Get $get) {
                                                if (empty($get('pt_no'))) {
                                                    return $rule->whereNull('pt_no');
                                                }
                                                return $rule->whereNotNull('pt_no');
                                            }),
                                        TextInput::make('pt_amount')
                                            ->label('PT Amount')
                                            ->numeric()
                                            ->step(0.01)
                                            ->inputMode('decimal')
                                            ->nullable(),
                                        DatePicker::make('pt_join_date')
                                            ->label('PT Join Date')
                                            ->nullable(),
                                        TextInput::make('pt_state')
                                            ->label('PT State')
                                            ->maxLength(255)
                                            ->nullable(),
                                        TextInput::make('pt_city')
                                            ->label('PT City')
                                            ->maxLength(255)
                                            ->nullable(),
                                        TextInput::make('pt_zone')
                                            ->label('PT Zone')
                                            ->maxLength(255)
                                            ->nullable(),
                                        TextInput::make('pt_code')
                                            ->label('PT Code')
                                            ->maxLength(255)
                                            ->nullable(),
                                        TextInput::make('pt_jv_code')
                                            ->label('PT JV Code')
                                            ->maxLength(255)
                                            ->nullable(),
                                        TextInput::make('pt_jv_code_cr')
                                            ->label('PT JV Code CR')
                                            ->maxLength(255)
                                            ->nullable(),
                                        TextInput::make('pt_jv_code_dr')
                                            ->label('PT JV Code DR')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Textarea::make('pt_remarks')
                                            ->label('PT Remarks')
                                            ->maxLength(65535)
                                            ->nullable()
                                            ->columnSpanFull(),
                                        Textarea::make('pt_jv_code_remarks')
                                            ->label('PT JV Code Remarks')
                                            ->maxLength(65535)
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])->columns(3) // Layout fields in 3 columns within this repeater
                                    ->defaultItems(1)
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['tax_type'] ?? null),
                            ]),

                        // Uncommented and converted Statutory IDs to a Tab
                        Tab::make('Statutory IDs')
                            ->icon('heroicon-o-identification')
                            ->disabled(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                            ->schema([
                                Section::make('Note')
                                    // This section is shown only when creating a new record
                                    ->description('This section is only available after saving the General Information.')
                                    ->visible(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                                    ->columnSpanFull()
                                    ->hiddenLabel() // Hide the default label for a cleaner look
                                    ->extraAttributes([
                                        'class' => 'p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md mb-4',
                                    ]),
                                Group::make() // Use a Group to organize fields within the tab
                                    ->relationship('statutoryIds') // Relates to the hasOne statutoryId method
                                    ->schema([
                                        TextInput::make('pan')
                                            ->label('PAN')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                // Ignore null values when checking for uniqueness, if PAN can be null
                                                return $rule->whereNotNull('pan');
                                            })
                                            ->hint('Permanent Account Number'),
                                        TextInput::make('uan_no')
                                            ->label('UAN Number')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                return $rule->whereNotNull('uan_no');
                                            }),
                                        DatePicker::make('group_join_date')
                                            ->label('Group Join Date')
                                            ->nullable(),
                                        TextInput::make('gratuity_code')
                                            ->label('Gratuity Code')
                                            ->maxLength(100)
                                            ->nullable(),
                                        TextInput::make('pran')
                                            ->label('PRAN')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                return $rule->whereNotNull('pran');
                                            }),
                                        TextInput::make('aadhar_number')
                                            ->label('Aadhar Number')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                return $rule->whereNotNull('aadhar_number');
                                            }),
                                        TextInput::make('tax_code')
                                            ->label('Tax Code')
                                            ->maxLength(100)
                                            ->nullable(),
                                        TextInput::make('tax_exemption')
                                            ->label('Tax Exemption')
                                            ->maxLength(255) // Assuming string for general text
                                            ->nullable(),
                                        TextInput::make('tax_exemption_reason')
                                            ->label('Tax Exemption Reason')
                                            ->maxLength(255)
                                            ->nullable(),
                                        DatePicker::make('tax_exemption_validity')
                                            ->label('Tax Exemption Validity')
                                            ->nullable(),
                                        Textarea::make('tax_exemption_remarks')
                                            ->label('Tax Exemption Remarks')
                                            ->maxLength(65535)
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])->columns(2), // Layout fields in 2 columns within this group
                            ]),

                        // Uncommented and converted Qualifications to a Tab
                        Tab::make('Qualifications')
                            ->icon('heroicon-o-academic-cap')
                            ->disabled(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                            ->schema([
                                Section::make('Note')
                                    // This section is shown only when creating a new record
                                    ->description('This section is only available after saving the General Information.')
                                    ->visible(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                                    ->columnSpanFull()
                                    ->hiddenLabel() // Hide the default label for a cleaner look
                                    ->extraAttributes([
                                        'class' => 'p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md mb-4',
                                    ]),
                                Repeater::make('qualifications')
                                    ->label('Academic Qualifications')
                                    // Keeping columns(3) as it is already optimal for 3 fields
                                    ->columns(3)
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('degree')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        TextInput::make('institution')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        TextInput::make('year_of_completion')
                                            ->numeric()
                                            ->rules(['digits:4', 'min:1900', 'max:' . (date('Y') + 5)]) // Sensible year range
                                            ->nullable()
                                            ->columnSpan(1),
                                        TextInput::make('certification')
                                            ->maxLength(255)
                                            ->nullable()
                                            ->columnSpan(1),
                                        TextInput::make('grade')
                                            ->maxLength(50)
                                            ->nullable()
                                            ->columnSpan(1),
                                        TextInput::make('percentage')
                                            ->numeric()
                                            ->step(0.01)
                                            ->inputMode('decimal')
                                            ->nullable()
                                            ->columnSpan(1),
                                        Textarea::make('remarks')
                                            ->maxLength(65535)
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])
                                    ->defaultItems(1)
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => ($state['degree'] ?? '') . ' from ' . ($state['institution'] ?? null)),
                            ]),

                        // Uncommented and converted Skills to a Tab
                        Tab::make('Skills')
                            ->icon('heroicon-o-sparkles')
                            ->disabled(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                            ->schema([
                                Section::make('Note')
                                    // This section is shown only when creating a new record
                                    ->description('This section is only available after saving the General Information.')
                                    ->visible(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                                    ->columnSpanFull()
                                    ->hiddenLabel() // Hide the default label for a cleaner look
                                    ->extraAttributes([
                                        'class' => 'p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md mb-4',
                                    ]),
                                Repeater::make('skills')
                                    ->label('Employee Skills')
                                    // Keeping columns(2) as it is already optimal for 2 fields
                                    ->columns(2)
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('skill_name')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        TextInput::make('proficiency_level')
                                            ->maxLength(255)
                                            ->nullable()
                                            ->columnSpan(1),
                                        Textarea::make('remarks')
                                            ->maxLength(65535)
                                            ->nullable()
                                            ->columnSpanFull(),
                                    ])
                                    ->defaultItems(1)
                                    ->reorderableWithButtons()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => ($state['skill_name'] ?? '') . ' (' . ($state['proficiency_level'] ?? '') . ')' ?? null),
                            ]),

                    ])->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('middle_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('gender')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('marital_status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('blood_group')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('country.name')
                    ->label('Nationality')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('mobile_number')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                TextColumn::make('employmentDetail.department.department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                TextColumn::make('employmentDetail.jobTitle.title')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                TextColumn::make('employmentDetail.employment_type')
                    ->label('Employment Type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                TextColumn::make('employmentDetail.employment_status')
                    ->label('Employment Status')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Active'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Is Active')
                    ->trueLabel('Active Employees')
                    ->falseLabel('Inactive Employees')
                    ->indicator('Active Status'), // Label for the indicator when filter is applied

                SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ])
                    ->label('Gender'),

                SelectFilter::make('marital_status')
                    ->options([
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Divorced' => 'Divorced',
                    ])
                    ->label('Marital Status'),

                SelectFilter::make('blood_group')
                    ->options([
                        'A+' => 'A+', 'A-' => 'A-',
                        'B+' => 'B+', 'B-' => 'B-',
                        'AB+' => 'AB+', 'AB-' => 'AB-',
                        'O+' => 'O+', 'O-' => 'O-',
                    ])
                    ->label('Blood Group'),

                SelectFilter::make('country_id')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Nationality'),

                SelectFilter::make('employment_type')
                    ->options([
                        'Permanent' => 'Permanent',
                        'Contract' => 'Contract',
                        'Part-Time' => 'Part-Time',
                        'Intern' => 'Intern',
                        'Temporary' => 'Temporary',
                        'Consultant' => 'Consultant',
                    ])
                    ->label('Employment Type'),

                SelectFilter::make('employment_status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Terminated' => 'Terminated',
                        'Retired' => 'Retired',
                        'On Leave' => 'On Leave',
                    ])
                    ->label('Employment Status'),

                SelectFilter::make('department')
                    ->relationship('employmentDetail.department', 'department_name')
                    ->searchable()
                    ->preload()
                    ->label('Department'),

                SelectFilter::make('job_title')
                    ->relationship('employmentDetail.jobTitle', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Job Title'),

                SelectFilter::make('grade')
                    ->relationship('employmentDetail.grade', 'grade_name')
                    ->searchable()
                    ->preload()
                    ->label('Grade'),

                SelectFilter::make('division')
                    ->relationship('employmentDetail.division', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Division'),

                SelectFilter::make('organizational_unit')
                    ->relationship('employmentDetail.organizationalUnit', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Organizational Unit'),

                Filter::make('hire_date')
                    ->schema([
                        DatePicker::make('hire_from')
                            ->placeholder('Hire Date From'),
                        DatePicker::make('hire_to')
                            ->placeholder('Hire Date To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['hire_from'],
                                fn (Builder $query, $date): Builder => $query->whereHas('employmentDetail', fn (Builder $subQuery) => $subQuery->whereDate('hire_date', '>=', $date)),
                            )
                            ->when(
                                $data['hire_to'],
                                fn (Builder $query, $date): Builder => $query->whereHas('employmentDetail', fn (Builder $subQuery) => $subQuery->whereDate('hire_date', '<=', $date)),
                            );
                    })
                    ->label('Hire Date Range'),

                Filter::make('last_increment_date')
                    ->schema([
                        DatePicker::make('increment_from')
                            ->placeholder('Increment Date From'),
                        DatePicker::make('increment_to')
                            ->placeholder('Increment Date To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['increment_from'],
                                fn (Builder $query, $date): Builder => $query->whereHas('employmentDetail', fn (Builder $subQuery) => $subQuery->whereDate('last_increment_date', '>=', $date)),
                            )
                            ->when(
                                $data['increment_to'],
                                fn (Builder $query, $date): Builder => $query->whereHas('employmentDetail', fn (Builder $subQuery) => $subQuery->whereDate('last_increment_date', '<=', $date)),
                            );
                    })
                    ->label('Last Increment Date Range'),
            ])->filtersFormColumns(2)
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BankDetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
