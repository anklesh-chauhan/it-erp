<?php

namespace App\Filament\Resources\EmpDepartmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EmploymentDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'employmentDetails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('ticket_no')
                    ->maxLength(20)
                    ->nullable(),
                Forms\Components\Select::make('job_title_id')
                    ->relationship('jobTitle', 'title')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('grade_id')
                    ->relationship('grade', 'grade_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('division_id')
                    ->relationship('division', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('organizational_unit_id')
                    ->relationship('organizationalUnit', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\DatePicker::make('hire_date')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('employment_type')
                    ->options([
                        'Permanent' => 'Permanent',
                        'Contract' => 'Contract',
                        'Intern' => 'Intern',
                        'Temporary' => 'Temporary',
                        'Consultant' => 'Consultant',
                    ])
                    ->nullable(),
                Forms\Components\Select::make('employment_status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Terminated' => 'Terminated',
                        'Retired' => 'Retired',
                        'On Leave' => 'On Leave',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('resign_offer_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\DatePicker::make('last_working_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\DatePicker::make('probation_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\DatePicker::make('confirm_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\DatePicker::make('fnf_retiring_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\DatePicker::make('last_increment_date')
                    ->native(false)
                    ->nullable(),
                Forms\Components\Select::make('work_location_id')
                    ->relationship('workLocation', 'location_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('reporting_manager_id')
                    ->relationship('reportingManager', 'full_name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull()
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.full_name')
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name'),
                Tables\Columns\TextColumn::make('ticket_no'),
                Tables\Columns\TextColumn::make('jobTitle.title'),
                Tables\Columns\TextColumn::make('grade.grade_name'),
                Tables\Columns\TextColumn::make('division.name'),
                Tables\Columns\TextColumn::make('organizationalUnit.name'),
                Tables\Columns\TextColumn::make('hire_date')
                    ->date(),
                Tables\Columns\TextColumn::make('employment_status'),
                Tables\Columns\TextColumn::make('workLocation.location_name'),
                Tables\Columns\TextColumn::make('reportingManager.full_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->disabled(fn () => $this->getOwnerRecord()->employmentDetails()->count() > 0
                        && $this->getOwnerRecord()->employmentDetails()->where('employment_status', 'Active')->exists()
                    )
                    ->modalHeading('Add Employment Details')
                    ->modalDescription('You can only add one active employment detail per employee.'),
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
}
