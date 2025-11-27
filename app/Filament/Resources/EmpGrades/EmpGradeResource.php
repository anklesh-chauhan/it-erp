<?php

namespace App\Filament\Resources\EmpGrades;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\EmpGrades\Pages\ListEmpGrades;
use App\Filament\Resources\EmpGrades\Pages\CreateEmpGrade;
use App\Filament\Resources\EmpGrades\Pages\EditEmpGrade;
use App\Filament\Resources\EmpGradeResource\Pages;
use App\Filament\Resources\EmpGradeResource\RelationManagers;
use App\Models\EmpGrade;
use App\Models\EmpDeparment; // Make sure to import the Department model
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpGradeResource extends Resource
{
    protected static ?string $model = EmpGrade::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar'; // Icon for grades

    protected static string | \UnitEnum | null $navigationGroup = 'HR & Organization';

    protected static ?string $navigationLabel = 'Grades';

    protected static ?int $navigationSort = 4; // Adjust sort order as needed

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Grade Details')
                    ->columns(2) // Arrange fields in two columns for a more inline look
                    ->schema([
                        TextInput::make('grade_name')
                            ->label('Grade Name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true), // Ensure grade names are unique
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'department_name') // Assuming EmpDeparment has a 'department_name' column
                            ->searchable()
                            ->preload()
                            ->nullable(), // Make nullable if a grade can exist without a specific department
                        Textarea::make('description')
                            ->label('Description')
                            ->maxLength(65535) // TEXT type in database
                            ->columnSpanFull() // This field correctly spans full width
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grade_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(50) // Limit description length in table for readability
                    ->toggleable(isToggledHiddenByDefault: false), // Show by default
                TextColumn::make('department.department_name') // Display department name
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
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
                // Filter by department
                SelectFilter::make('department')
                    ->relationship('department', 'department_name')
                    ->label('Filter by Department')
                    ->preload(),
            ])
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
            // You can add relation managers here if needed, e.g., to list employees with this grade
            // RelationManagers\EmploymentDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmpGrades::route('/'),
            'create' => CreateEmpGrade::route('/create'),
            'edit' => EditEmpGrade::route('/{record}/edit'),
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
