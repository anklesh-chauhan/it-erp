<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpDivisionResource\Pages;
use App\Filament\Resources\EmpDivisionResource\RelationManagers;
use App\Models\EmpDivision;
use App\Models\EmpDeparment; // Make sure to import the Department model
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpDivisionResource extends Resource
{
    protected static ?string $model = EmpDivision::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; // Icon for divisions

    protected static ?string $navigationGroup = 'HR & Organization';

    protected static ?string $navigationLabel = 'Divisions';

    protected static ?int $navigationSort = 5; // Adjust sort order as needed

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Division Details')
                    ->columns(2) // Arrange fields in two columns for a more inline look
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Division Name')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true), // Ensure division names are unique
                        Forms\Components\Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'department_name') // Assuming EmpDeparment has a 'department_name' column
                            ->searchable()
                            ->preload()
                            ->nullable(), // Make nullable if a division can exist without a specific department
                        Forms\Components\Textarea::make('description')
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50) // Limit description length in table for readability
                    ->toggleable(isToggledHiddenByDefault: false), // Show by default
                Tables\Columns\TextColumn::make('department.department_name') // Display department name
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
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
                // Filter by department
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'department_name')
                    ->label('Filter by Department')
                    ->preload(),
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
            // You can add relation managers here if needed, e.g., to list employees within this division
            // RelationManagers\EmploymentDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpDivisions::route('/'),
            'create' => Pages\CreateEmpDivision::route('/create'),
            'edit' => Pages\EditEmpDivision::route('/{record}/edit'),
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
