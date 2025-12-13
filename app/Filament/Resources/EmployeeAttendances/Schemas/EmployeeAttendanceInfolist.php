<?php

namespace App\Filament\Resources\EmployeeAttendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeAttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('employee.id')
                    ->label('Employee'),
                TextEntry::make('attendance_date')
                    ->date(),
                TextEntry::make('check_in')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('check_out')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('total_hours')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status.id')
                    ->label('Status'),
                TextEntry::make('check_in_ip')
                    ->placeholder('-'),
                TextEntry::make('check_out_ip')
                    ->placeholder('-'),
                TextEntry::make('check_in_latitude')
                    ->placeholder('-'),
                TextEntry::make('check_in_longitude')
                    ->placeholder('-'),
                TextEntry::make('check_out_latitude')
                    ->placeholder('-'),
                TextEntry::make('check_out_longitude')
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
