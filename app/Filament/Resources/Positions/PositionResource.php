<?php

namespace App\Filament\Resources\Positions;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Positions\Pages\ListPositions;
use App\Filament\Resources\Positions\Pages\CreatePosition;
use App\Filament\Resources\Positions\Pages\EditPosition;
use App\Enums\PositionStatus;
use App\Filament\Resources\PositionResource\Pages;
use App\Models\Position;
use App\Models\Employee;
use App\Models\OrganizationalUnit;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';
    protected static string | \UnitEnum | null $navigationGroup = 'HR & Organization';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Position Details')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('code')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                    Select::make('status')
                        ->options(PositionStatus::class)
                        ->default(PositionStatus::Active)
                        ->required(),
                    TextInput::make('level')
                        ->nullable()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->nullable()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ]),

            Section::make('Reporting & Organizational Structure')
                ->columns(2)
                ->schema([
                    Select::make('reports_to_position_id')
                        ->label('Reports To Position')
                        ->relationship('reportsTo', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a reporting position'),
                    
                    Toggle::make('is_multi_territory')
                        ->label('Is Multi Territory')
                        ->helperText('Enable to select multiple territories. ⚠️ Once multi-territory is enabled, it cannot be reversed.')
                        ->reactive()
                        ->disabled(fn (?Position $record, Get $get) => $record?->is_multi_territory ?? false),

                    Select::make('territories')
                        ->relationship('territories', 'name')
                        ->multiple() // Keep it multiple for the many-to-many relationship
                        ->searchable()
                        ->preload()
                        ->label(fn (Get $get) => $get('is_multi_territory') ? 'Territories (Select Multiple)' : 'Territory (Select One Only)')
                        
                        // Add validation to limit selection to 1 when NOT multi-territory
                        ->rules([
                            fn (Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                if (! $get('is_multi_territory') && count($value) > 1) {
                                    $fail("Only one territory can be selected when 'Is Multi Territory' is disabled.");
                                }
                            },
                        ])
                        // The component is always visible, but its label changes and it is constrained
                        ->helperText(fn (Get $get) => ! $get('is_multi_territory') ? 'Select a single territory.' : null),

                    Select::make('location_id')
                        ->label('Location')
                        ->relationship('location', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a location'),

                    Select::make('organizationalUnits')
                        ->multiple()
                        ->relationship('organizationalUnits', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Organizational Units'),
                ]),

            Section::make('Job Classification')
                ->columns(2)
                ->schema([
                    Select::make('division_id')
                        ->label('Division')
                        ->relationship('division', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a division'),

                    Select::make('department_id')
                        ->label('Department')
                        ->relationship('department', 'department_name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a department'),

                    Select::make('job_title_id')
                        ->label('Job Title')
                        ->relationship('jobTitle', 'title')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a job title'),

                    Select::make('job_grade_id')
                        ->label('Job Grade')
                        ->relationship('jobGrade', 'grade_name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a job grade'),
                ]),

            Section::make('Assigned Employees')
                ->columns(1)
                ->description('Select employees assigned to this position.')
                ->schema([
                    Select::make('employees')
                        ->relationship('employees', 'first_name')
                        ->getOptionLabelFromRecordUsing(fn (Employee $record) =>
                            "{$record->first_name} {$record->last_name} ({$record->employee_code})"
                        )
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Select Employees'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->searchable()->sortable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->badge()
                //     ->color(fn (string $state): string => PositionStatus::from($state)->getColor())
                //     ->sortable(),

                TextColumn::make('level')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('reportsTo.name')
                    ->label('Reports To')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('territories')
                    ->label('Territories')
                    ->formatStateUsing(fn ($record) =>
                        $record->territories->pluck('name')->implode(', ')
                    )
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('division.name')
                    ->label('Division')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('department.department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('jobTitle.title')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('jobGrade.grade_name')
                    ->label('Job Grade')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('organizationalUnits.name')
                    ->label('Org. Units')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('status')
                //     ->options(PositionStatus::class)
                //     ->label('Status'),

                SelectFilter::make('territories')
                    ->relationship('territories', 'name')
                    ->multiple()
                    ->label('Filter by Territory'),

                SelectFilter::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('reports_to_position_id')
                    ->label('Reports To')
                    ->relationship('reportsTo', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('division_id')
                    ->label('Division')
                    ->relationship('division', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'department_name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('job_title_id')
                    ->label('Job Title')
                    ->relationship('jobTitle', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('job_grade_id')
                    ->label('Job Grade')
                    ->relationship('jobGrade', 'grade_name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('organizationalUnits')
                    ->relationship('organizationalUnits', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Organizational Units'),
            ])->filtersFormColumns(2)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPositions::route('/'),
            'create' => CreatePosition::route('/create'),
            'edit' => EditPosition::route('/{record}/edit'),
        ];
    }
}
