<?php

namespace App\Filament\Resources\EmpJobTitles;

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
use App\Filament\Resources\EmpJobTitles\Pages\ListEmpJobTitles;
use App\Filament\Resources\EmpJobTitles\Pages\CreateEmpJobTitle;
use App\Filament\Resources\EmpJobTitles\Pages\EditEmpJobTitle;
use App\Filament\Resources\EmpJobTitleResource\Pages;
use App\Filament\Resources\EmpJobTitleResource\RelationManagers;
use App\Models\EmpJobTitle;
use App\Models\EmpDeparment; // Make sure to import the Department model
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpJobTitleResource extends Resource
{
    protected static ?string $model = EmpJobTitle::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag'; // Changed icon to something more suitable for job titles

    protected static string | \UnitEnum | null $navigationGroup = 'HR & Organization'; // Keep in the same group as Employee

    protected static ?string $navigationLabel = 'Job Titles';

    protected static ?int $navigationSort = 3; // Adjust sort order as needed, e.g., after Departments

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Job Title Details')
                    ->columns(2) // Arrange fields in two columns for a more inline look
                    ->schema([
                        TextInput::make('title')
                            ->label('Job Title')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true), // Ensure job titles are unique
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'department_name') // Assuming EmpDeparment has a 'department_name' column
                            ->searchable()
                            ->preload()
                            ->nullable(), // Make nullable if a job title can exist without a specific department
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
                TextColumn::make('title')
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
            // You can add relation managers here if needed, e.g., to list employees with this job title
            // RelationManagers\EmploymentDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmpJobTitles::route('/'),
            'create' => CreateEmpJobTitle::route('/create'),
            'edit' => EditEmpJobTitle::route('/{record}/edit'),
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
