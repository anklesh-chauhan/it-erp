<?php

namespace App\Filament\Resources;

use App\Enums\PositionStatus; // Import the PositionStatus enum
use App\Filament\Resources\PositionResource\Pages;
use App\Filament\Resources\PositionResource\RelationManagers;
use App\Models\Position;
use App\Models\Employee; // Assuming this model exists
use App\Models\Territory; // Assuming this model exists
use App\Models\EmpDivision; // Assuming this model exists
use App\Models\EmpDepartment; // Assuming this model exists
use App\Models\EmpJobTitle; // Assuming this model exists
use App\Models\EmpGrade; // Assuming this model exists
use App\Models\LocationMaster; // Assuming this model exists
use App\Models\OrganizationalUnit; // Assuming this model exists
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; // If you use soft deletes

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; // Choose an appropriate icon

    protected static ?string $navigationGroup = 'HR & Organization'; // Group under HR or Organization

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Position Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('code')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Select::make('status')
                            ->options(PositionStatus::class) // Using the enum for options
                            ->default(PositionStatus::Active)
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('level')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Reporting & Organizational Structure')
                    ->schema([
                        Forms\Components\Select::make('reports_to_position_id')
                            ->label('Reports To Position')
                            ->relationship('reportsTo', 'name') // Self-referencing relationship
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a reporting position'),
                        Forms\Components\Select::make('territory_id')
                            ->label('Territory')
                            ->relationship('territory', 'name')
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a territory'),
                        Forms\Components\Select::make('location_id')
                            ->label('Location')
                            ->relationship('location', 'name') // Assuming LocationMaster has a 'name' column
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a location'),
                        Forms\Components\Select::make('organizationalUnits')
                            ->multiple()
                            ->relationship('organizationalUnits', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Associated Organizational Units'),
                    ])->columns(2),

                Forms\Components\Section::make('Job Classification')
                    ->schema([
                        Forms\Components\Select::make('division_id')
                            ->label('Division')
                            ->relationship('division', 'name') // Assuming EmpDivision has a 'name' column
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a division'),
                        Forms\Components\Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name') // Assuming EmpDepartment has a 'name' column
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a department'),
                        Forms\Components\Select::make('job_title_id')
                            ->label('Job Title')
                            ->relationship('jobTitle', 'name') // Assuming EmpJobTitle has a 'name' column
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a job title'),
                        Forms\Components\Select::make('job_grade_id')
                            ->label('Job Grade')
                            ->relationship('jobGrade', 'name') // Assuming EmpGrade has a 'name' column
                            ->searchable()
                            ->nullable()
                            ->placeholder('Select a job grade'),
                    ])->columns(2),

                Forms\Components\Section::make('Assigned Employees')
                    ->description('Select employees assigned to this position.')
                    ->schema([
                        Forms\Components\Select::make('employees') // Directly use the 'employees' relationship
                            ->relationship('employees', 'first_name') // Display employee first name
                            ->getOptionLabelFromRecordUsing(fn (Employee $record) => "{$record->first_name} {$record->last_name} ({$record->employee_code})")
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Select Employees'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge() // Displays the status with a colored badge based on enum
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('reportsTo.name')
                    ->label('Reports To')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('territory.name')
                    ->label('Territory')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('division.name')
                    ->label('Division')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('department.department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jobTitle.title')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jobGrade.grade_name')
                    ->label('Job Grade')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('organizationalUnits.name')
                    ->label('Org. Units')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->searchable()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // Custom sort for many-to-many relationship
                        return $query->orderBy(
                            OrganizationalUnit::select('name')
                                ->whereColumn('position_organizational_unit_pivot.organizational_unit_id', 'organizational_units.id')
                                ->whereColumn('position_organizational_unit_pivot.position_id', 'positions.id')
                                ->limit(1),
                            $direction
                        );
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options(PositionStatus::class)
                    ->label('Status'),
                Tables\Filters\SelectFilter::make('territory_id')
                    ->label('Territory')
                    ->relationship('territory', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('reports_to_position_id')
                    ->label('Reports To')
                    ->relationship('reportsTo', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('division_id')
                    ->label('Division')
                    ->relationship('division', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'department_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('job_title_id')
                    ->label('Job Title')
                    ->relationship('jobTitle', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('job_grade_id')
                    ->label('Job Grade')
                    ->relationship('jobGrade', 'grade_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('organizationalUnits')
                    ->relationship('organizationalUnits', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Organizational Units'),
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
            // You can add relation managers here, e.g., to manage employees attached to positions
            // RelationManagers\EmployeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
