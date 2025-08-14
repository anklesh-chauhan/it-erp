<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Tabs; // Import Tabs
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab; // Import Tab

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'HR & Organization';

    protected static ?string $navigationLabel = 'Employees';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Changed from Wizard to Tabs
                Tabs::make('Employee Details')
                    ->columnSpanFull() // Ensure the tabs span the full width
                    ->tabs([
                        // Converted 'General Information' Step to a Tab
                        Tab::make('General Information')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Section::make('Personal Details')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('employee_id')
                                            ->label('Employee ID')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(20),
                                        Forms\Components\TextInput::make('first_name')
                                            ->required()
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('middle_name')
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('last_name')
                                            ->required()
                                            ->maxLength(50),
                                        Forms\Components\Select::make('gender')
                                            ->options([
                                                'Male' => 'Male',
                                                'Female' => 'Female',
                                                'Other' => 'Other',
                                            ])
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('date_of_birth')
                                            ->native(false)
                                            ->nullable(),
                                        Forms\Components\Select::make('marital_status')
                                            ->options([
                                                'Single' => 'Single',
                                                'Married' => 'Married',
                                                'Divorced' => 'Divorced',
                                            ])
                                            ->nullable(),
                                        Forms\Components\Select::make('blood_group')
                                            ->options([
                                                'A+' => 'A+', 'A-' => 'A-',
                                                'B+' => 'B+', 'B-' => 'B-',
                                                'AB+' => 'AB+', 'AB-' => 'AB-',
                                                'O+' => 'O+', 'O-' => 'O-',
                                            ])
                                            ->nullable(),
                                        Forms\Components\Select::make('country_id')
                                            ->relationship('country', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Nationality')
                                            ->nullable(),
                                    ]),
                                Forms\Components\Section::make('Contact Information')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(100)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('mobile_number')
                                            ->tel()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(100)
                                            ->required(),
                                        Forms\Components\TextInput::make('phone_number')
                                            ->tel()
                                            ->maxLength(15)
                                            ->nullable(),
                                        Forms\Components\Textarea::make('contact_details')
                                            ->columnSpanFull() // This field correctly spans full width
                                            ->nullable(),
                                    ]),
                                Forms\Components\Section::make('Emergency Contact')
                                    // Keeping columns(2) as there are only 2 fields
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('emergency_contact_name')
                                            ->maxLength(100)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('emergency_contact_number')
                                            ->tel()
                                            ->maxLength(15)
                                            ->nullable(),
                                    ]),
                                Forms\Components\Section::make('Additional Details')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\FileUpload::make('profile_picture')
                                            ->image()
                                            ->directory('employee-profiles')
                                            ->nullable(),
                                        Forms\Components\Toggle::make('is_active')
                                            ->default(true),
                                        Forms\Components\Select::make('login_id')
                                            ->relationship('user', 'email')
                                            ->searchable()
                                            ->preload()
                                            ->label('Linked User')
                                            ->nullable(),
                                        Forms\Components\Hidden::make('updated_by_user_id')
                                            ->default(optional(Auth::user())->id),
                                    ]),
                            ]),

                        // Converted 'Employment Details' Step to a Tab
                        Tab::make('Employment Details')
                            ->icon('heroicon-o-briefcase')
                            ->disabled(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                            ->schema([
                                Forms\Components\Section::make('Note')
                                    // This section is shown only when creating a new record
                                    ->description('This section is only available after saving the General Information.')
                                    ->visible(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                                    ->columnSpanFull()
                                    ->hiddenLabel() // Hide the default label for a cleaner look
                                    ->extraAttributes([
                                        'class' => 'p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md mb-4',
                                    ]),
                                Forms\Components\Section::make('Current Employment')
                                    // Changed from columns(2) to columns(3) for a denser layout
                                    ->columns(3)
                                    ->relationship('employmentDetail') // Binds to the employmentDetail relationship
                                    ->schema([
                                        Forms\Components\Select::make('reporting_manager_id')
                                            ->label('Reporting Manager')
                                            ->options(fn () => Employee::all()->pluck('full_name', 'id')->toArray())
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        Forms\Components\Select::make('department_id')
                                            ->label('Department')
                                            ->relationship('department', 'department_name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        Forms\Components\Select::make('job_title_id')
                                            ->label('Job Title')
                                            ->relationship('jobTitle', 'title')
                                            ->preload()
                                            ->searchable()
                                            ->requiredIf('employee_id', true),
                                        Forms\Components\Select::make('grade_id')
                                            ->label('Grade')
                                            ->relationship('grade', 'grade_name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('grade_name')
                                                    ->label('Grade Name')
                                                    ->required()
                                                    ->maxLength(100),
                                            ]),
                                        Forms\Components\Select::make('division_id')
                                            ->label('Division')
                                            ->relationship('division', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        Forms\Components\Select::make('organizational_unit_id')
                                            ->label('Organizational Unit')
                                            ->relationship('organizationalUnit', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('hire_date')
                                            ->label('Hire Date')
                                            ->native(false)
                                            ->requiredIf('employee_id', true),
                                        Forms\Components\Select::make('employment_type')
                                            ->label('Employment Type')
                                            ->options(['Permanent' => 'Permanent','Contract' => 'Contract', 'Part-Time' => 'Part-Time', 'Intern' => 'Intern', 'Temporary' => 'Temporary', 'Consultant' => 'Consultant'])
                                            ->nullable(),
                                        Forms\Components\Select::make('employment_status')
                                            ->label('Employment Status')
                                            ->options(['Active' => 'Active', 'Inactive' => 'Inactive', 'Terminated' => 'Terminated', 'Retired' => 'Retired', 'On Leave' => 'On Leave'])
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('resign_offer_date')
                                            ->label('Resign Offer Date')
                                            ->native(false)
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('last_working_date')
                                            ->label('Last Working Date')
                                            ->native(false)
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('probation_date')
                                            ->label('Probation Date')
                                            ->native(false)
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('confirm_date')
                                            ->label('Confirmation Date')
                                            ->native(false)
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('fnf_retiring_date')
                                            ->label('FNF/Retiring Date')
                                            ->native(false)
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('last_increment_date')
                                            ->label('Last Increment Date')
                                            ->native(false)
                                            ->nullable(),
                                        Forms\Components\Select::make('work_location_id')
                                            ->label('Work Location')
                                            ->relationship('workLocation', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->nullable(),

                                        Forms\Components\Textarea::make('remarks')
                                            ->label('Remarks')
                                            ->columnSpanFull()
                                            ->nullable(),
                                        Forms\Components\Hidden::make('created_by')
                                            ->default(optional(Auth::user())->id),
                                        Forms\Components\Hidden::make('updated_by')
                                            ->default(optional(Auth::user())->id),
                                    ]),
                                Forms\Components\Section::make('Assigned Positions')
                                    ->description('Select the positions this employee holds.')
                                    ->schema([
                                        Forms\Components\Select::make('positions')
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
                                Forms\Components\Section::make('Note')
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
                                        Forms\Components\Toggle::make('pt_flag')
                                            ->label('Professional Tax Applicable')
                                            ->inline(false)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_no')
                                            ->label('PT Number')
                                            ->maxLength(255)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule, Forms\Get $get) {
                                                if (empty($get('pt_no'))) {
                                                    return $rule->whereNull('pt_no');
                                                }
                                                return $rule->whereNotNull('pt_no');
                                            }),
                                        Forms\Components\TextInput::make('pt_amount')
                                            ->label('PT Amount')
                                            ->numeric()
                                            ->step(0.01)
                                            ->inputMode('decimal')
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('pt_join_date')
                                            ->label('PT Join Date')
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_state')
                                            ->label('PT State')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_city')
                                            ->label('PT City')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_zone')
                                            ->label('PT Zone')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_code')
                                            ->label('PT Code')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_jv_code')
                                            ->label('PT JV Code')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_jv_code_cr')
                                            ->label('PT JV Code CR')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pt_jv_code_dr')
                                            ->label('PT JV Code DR')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\Textarea::make('pt_remarks')
                                            ->label('PT Remarks')
                                            ->maxLength(65535)
                                            ->nullable()
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('pt_jv_code_remarks')
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
                                Forms\Components\Section::make('Note')
                                    // This section is shown only when creating a new record
                                    ->description('This section is only available after saving the General Information.')
                                    ->visible(fn (string $operation, ?Employee $record): bool => $operation === 'create' && !$record?->exists)
                                    ->columnSpanFull()
                                    ->hiddenLabel() // Hide the default label for a cleaner look
                                    ->extraAttributes([
                                        'class' => 'p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md mb-4',
                                    ]),
                                Forms\Components\Group::make() // Use a Group to organize fields within the tab
                                    ->relationship('statutoryIds') // Relates to the hasOne statutoryId method
                                    ->schema([
                                        Forms\Components\TextInput::make('pan')
                                            ->label('PAN')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule) {
                                                // Ignore null values when checking for uniqueness, if PAN can be null
                                                return $rule->whereNotNull('pan');
                                            })
                                            ->hint('Permanent Account Number'),
                                        Forms\Components\TextInput::make('uan_no')
                                            ->label('UAN Number')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule) {
                                                return $rule->whereNotNull('uan_no');
                                            }),
                                        Forms\Components\DatePicker::make('group_join_date')
                                            ->label('Group Join Date')
                                            ->nullable(),
                                        Forms\Components\TextInput::make('gratuity_code')
                                            ->label('Gratuity Code')
                                            ->maxLength(100)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('pran')
                                            ->label('PRAN')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule) {
                                                return $rule->whereNotNull('pran');
                                            }),
                                        Forms\Components\TextInput::make('aadhar_number')
                                            ->label('Aadhar Number')
                                            ->maxLength(100)
                                            ->nullable()
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (\Illuminate\Validation\Rules\Unique $rule) {
                                                return $rule->whereNotNull('aadhar_number');
                                            }),
                                        Forms\Components\TextInput::make('tax_code')
                                            ->label('Tax Code')
                                            ->maxLength(100)
                                            ->nullable(),
                                        Forms\Components\TextInput::make('tax_exemption')
                                            ->label('Tax Exemption')
                                            ->maxLength(255) // Assuming string for general text
                                            ->nullable(),
                                        Forms\Components\TextInput::make('tax_exemption_reason')
                                            ->label('Tax Exemption Reason')
                                            ->maxLength(255)
                                            ->nullable(),
                                        Forms\Components\DatePicker::make('tax_exemption_validity')
                                            ->label('Tax Exemption Validity')
                                            ->nullable(),
                                        Forms\Components\Textarea::make('tax_exemption_remarks')
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
                                Forms\Components\Section::make('Note')
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
                                        Forms\Components\TextInput::make('degree')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('institution')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('year_of_completion')
                                            ->numeric()
                                            ->rules(['digits:4', 'min:1900', 'max:' . (date('Y') + 5)]) // Sensible year range
                                            ->nullable()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('certification')
                                            ->maxLength(255)
                                            ->nullable()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('grade')
                                            ->maxLength(50)
                                            ->nullable()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('percentage')
                                            ->numeric()
                                            ->step(0.01)
                                            ->inputMode('decimal')
                                            ->nullable()
                                            ->columnSpan(1),
                                        Forms\Components\Textarea::make('remarks')
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
                                Forms\Components\Section::make('Note')
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
                                        Forms\Components\TextInput::make('skill_name')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('proficiency_level')
                                            ->maxLength(255)
                                            ->nullable()
                                            ->columnSpan(1),
                                        Forms\Components\Textarea::make('remarks')
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
                Tables\Columns\TextColumn::make('employee_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('gender')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('marital_status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('blood_group')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Nationality')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('mobile_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hidden by default
                Tables\Columns\TextColumn::make('employmentDetail.department.department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                Tables\Columns\TextColumn::make('employmentDetail.jobTitle.title')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                Tables\Columns\TextColumn::make('employmentDetail.employment_type')
                    ->label('Employment Type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                Tables\Columns\TextColumn::make('employmentDetail.employment_status')
                    ->label('Employment Status')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false), // Visible by default
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Is Active')
                    ->trueLabel('Active Employees')
                    ->falseLabel('Inactive Employees')
                    ->indicator('Active Status'), // Label for the indicator when filter is applied

                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ])
                    ->label('Gender'),

                Tables\Filters\SelectFilter::make('marital_status')
                    ->options([
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Divorced' => 'Divorced',
                    ])
                    ->label('Marital Status'),

                Tables\Filters\SelectFilter::make('blood_group')
                    ->options([
                        'A+' => 'A+', 'A-' => 'A-',
                        'B+' => 'B+', 'B-' => 'B-',
                        'AB+' => 'AB+', 'AB-' => 'AB-',
                        'O+' => 'O+', 'O-' => 'O-',
                    ])
                    ->label('Blood Group'),

                Tables\Filters\SelectFilter::make('country_id')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Nationality'),

                Tables\Filters\SelectFilter::make('employment_type')
                    ->options([
                        'Permanent' => 'Permanent',
                        'Contract' => 'Contract',
                        'Part-Time' => 'Part-Time',
                        'Intern' => 'Intern',
                        'Temporary' => 'Temporary',
                        'Consultant' => 'Consultant',
                    ])
                    ->label('Employment Type'),

                Tables\Filters\SelectFilter::make('employment_status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Terminated' => 'Terminated',
                        'Retired' => 'Retired',
                        'On Leave' => 'On Leave',
                    ])
                    ->label('Employment Status'),

                Tables\Filters\SelectFilter::make('department')
                    ->relationship('employmentDetail.department', 'department_name')
                    ->searchable()
                    ->preload()
                    ->label('Department'),

                Tables\Filters\SelectFilter::make('job_title')
                    ->relationship('employmentDetail.jobTitle', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Job Title'),

                Tables\Filters\SelectFilter::make('grade')
                    ->relationship('employmentDetail.grade', 'grade_name')
                    ->searchable()
                    ->preload()
                    ->label('Grade'),

                Tables\Filters\SelectFilter::make('division')
                    ->relationship('employmentDetail.division', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Division'),

                Tables\Filters\SelectFilter::make('organizational_unit')
                    ->relationship('employmentDetail.organizationalUnit', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Organizational Unit'),

                Tables\Filters\Filter::make('hire_date')
                    ->form([
                        Forms\Components\DatePicker::make('hire_from')
                            ->placeholder('Hire Date From'),
                        Forms\Components\DatePicker::make('hire_to')
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

                Tables\Filters\Filter::make('last_increment_date')
                    ->form([
                        Forms\Components\DatePicker::make('increment_from')
                            ->placeholder('Increment Date From'),
                        Forms\Components\DatePicker::make('increment_to')
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
            // Relation Managers are not needed since tabs handle related data
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
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
