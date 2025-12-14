<?php

namespace App\Filament\Resources\EmployeeAttendanceStatuses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendanceStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('status_code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Code')
                    ->disabled(fn (?Model $record) =>
                        $record && (
                            $record->is_system ||
                            $record->attendances()->exists()
                        )
                    )
                    ->helperText(fn (?Model $record) =>
                        $record?->is_system
                            ? 'System-defined status code cannot be changed.'
                            : ($record->attendances()->exists()
                                ? 'Code cannot be changed once used in attendance records.'
                                : null
                            )
                    ),

                Forms\Components\TextInput::make('status')
                    ->required()
                    ->label('Status Name'),

                Forms\Components\ColorPicker::make('color_code')
                    ->label('Color')
                    ->required()
                    ->default('#009900')
                    ->helperText('Used for status badge and calendar display'),

                Forms\Components\Textarea::make('remarks')
                    ->label('Remarks')
                    ->rows(3),
            ]);
    }
}
