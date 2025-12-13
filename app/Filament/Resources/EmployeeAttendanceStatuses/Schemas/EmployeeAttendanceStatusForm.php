<?php

namespace App\Filament\Resources\EmployeeAttendanceStatuses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class EmployeeAttendanceStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('status_code')
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Code'),

                Forms\Components\TextInput::make('status')
                    ->required()
                    ->label('Status Name'),

                Forms\Components\Textarea::make('remarks')
                    ->label('Remarks')
                    ->rows(3),
            ]);
    }
}
