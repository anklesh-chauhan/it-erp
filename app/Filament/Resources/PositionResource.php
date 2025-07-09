<?php

namespace App\Filament\Resources;

use App\Enums\PositionStatus;
use App\Filament\Resources\PositionResource\Pages;
use App\Models\Position;
use App\Models\Employee;
use App\Models\OrganizationalUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'HR & Organization';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Position Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('code')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('status')
                        ->options(PositionStatus::class)
                        ->default(PositionStatus::Active)
                        ->required(),
                    Forms\Components\TextInput::make('level')
                        ->nullable()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->nullable()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Reporting & Organizational Structure')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('reports_to_position_id')
                        ->label('Reports To Position')
                        ->relationship('reportsTo', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a reporting position'),
                    
                    Forms\Components\Toggle::make('is_multi_territory')
                        ->label('Is Multi Territory')
                        ->helperText('Enable to select multiple territories. ⚠️ Once multi-territory is enabled, it cannot be reversed.')
                        ->reactive()
                        ->disabled(fn (?Position $record, Get $get) => $record?->is_multi_territory ?? false),
                    
                    Forms\Components\Select::make('territories')
                        ->relationship('territories', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->label('Territories')
                        ->visible(fn (Get $get) => $get('is_multi_territory')),
                    
                    Forms\Components\Select::make('territories')
                        ->relationship('territories', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Territory')
                        ->visible(fn (Get $get) => ! $get('is_multi_territory')),

                    Forms\Components\Select::make('location_id')
                        ->label('Location')
                        ->relationship('location', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a location'),

                    Forms\Components\Select::make('organizationalUnits')
                        ->multiple()
                        ->relationship('organizationalUnits', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Organizational Units'),
                ]),

            Forms\Components\Section::make('Job Classification')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('division_id')
                        ->label('Division')
                        ->relationship('division', 'name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a division'),

                    Forms\Components\Select::make('department_id')
                        ->label('Department')
                        ->relationship('department', 'department_name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a department'),

                    Forms\Components\Select::make('job_title_id')
                        ->label('Job Title')
                        ->relationship('jobTitle', 'title')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a job title'),

                    Forms\Components\Select::make('job_grade_id')
                        ->label('Job Grade')
                        ->relationship('jobGrade', 'grade_name')
                        ->searchable()
                        ->nullable()
                        ->placeholder('Select a job grade'),
                ]),

            Forms\Components\Section::make('Assigned Employees')
                ->columns(1)
                ->description('Select employees assigned to this position.')
                ->schema([
                    Forms\Components\Select::make('employees')
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
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->badge()
                //     ->color(fn (string $state): string => PositionStatus::from($state)->getColor())
                //     ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('reportsTo.name')
                    ->label('Reports To')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('territories')
                    ->label('Territories')
                    ->formatStateUsing(fn ($record) =>
                        $record->territories->pluck('name')->implode(', ')
                    )
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('division.name')
                    ->label('Division')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('department.department_name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jobTitle.title')
                    ->label('Job Title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jobGrade.grade_name')
                    ->label('Job Grade')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('organizationalUnits.name')
                    ->label('Org. Units')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('status')
                //     ->options(PositionStatus::class)
                //     ->label('Status'),

                Tables\Filters\SelectFilter::make('territories')
                    ->relationship('territories', 'name')
                    ->multiple()
                    ->label('Filter by Territory'),

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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
