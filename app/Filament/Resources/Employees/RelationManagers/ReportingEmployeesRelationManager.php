<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReportingEmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'reportingEmployees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ticket_no')
                    ->maxLength(20)
                    ->nullable(),
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'department_name')
                    ->searchable()
                    ->preload()
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
                Forms\Components\Select::make('organization_id')
                    ->relationship('organization', 'name')
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
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.full_name')
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name'),
                Tables\Columns\TextColumn::make('ticket_no'),
                Tables\Columns\TextColumn::make('department.department_name'),
                Tables\Columns\TextColumn::make('jobTitle.title'),
                Tables\Columns\TextColumn::make('employment_status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
