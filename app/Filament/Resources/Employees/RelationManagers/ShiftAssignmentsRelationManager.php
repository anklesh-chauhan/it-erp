<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class ShiftAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shiftAssignments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('shift_master_id')
                ->label('Shift')
                ->relationship('shift', 'name')
                ->required(),

                DatePicker::make('effective_from')
                    ->required()
                    ->rules([
                        function (callable $get, ?Model $record) {
                            return function ($attribute, $value, $fail) use ($get, $record) {
                                $employeeId = $get('employee_id') ?? $record?->employee_id;
                                $to = $get('effective_to') ?? '9999-12-31';

                                $exists = \App\Models\EmployeeShift::where('employee_id', $employeeId)
                                    ->where('id', '!=', $record?->id)
                                    ->where(function ($q) use ($value, $to) {
                                        $q->whereBetween('effective_from', [$value, $to])
                                        ->orWhereBetween('effective_to', [$value, $to])
                                        ->orWhere(function ($q) use ($value, $to) {
                                            $q->where('effective_from', '<=', $value)
                                                ->where(function ($q) use ($to) {
                                                    $q->whereNull('effective_to')
                                                    ->orWhere('effective_to', '>=', $to);
                                                });
                                        });
                                    })
                                    ->exists();

                                if ($exists) {
                                    $fail('Shift dates overlap with an existing assignment.');
                                }
                            };
                        },
                    ]),

                DatePicker::make('effective_to')
                    ->nullable(),

                Toggle::make('is_current')
                    ->default(true),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('shiftAssignments'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('shiftAssignments')
            ->defaultSort('effective_from', 'desc')
            ->columns([
                TextColumn::make('shift.name')->label('Shift'),
                TextColumn::make('effective_from')->date(),
                TextColumn::make('effective_to')->date(),
                IconColumn::make('is_current')->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
