<?php

namespace App\Filament\Resources\EmployeeAttendances;

use App\Filament\Resources\EmployeeAttendances\Pages\CreateEmployeeAttendance;
use App\Filament\Resources\EmployeeAttendances\Pages\EditEmployeeAttendance;
use App\Filament\Resources\EmployeeAttendances\Pages\ListEmployeeAttendances;
use App\Filament\Resources\EmployeeAttendances\Pages\ViewEmployeeAttendance;
use App\Filament\Resources\EmployeeAttendances\Schemas\EmployeeAttendanceForm;
use App\Filament\Resources\EmployeeAttendances\Schemas\EmployeeAttendanceInfolist;
use App\Filament\Resources\EmployeeAttendances\Tables\EmployeeAttendancesTable;
use App\Models\EmployeeAttendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use App\Traits\HasSafeGlobalSearch;

class EmployeeAttendanceResource extends Resource
{
    use HasSafeGlobalSearch;

    protected static ?string $model = EmployeeAttendance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'EmployeeAttendance';

    public static function form(Schema $schema): Schema
    {
        return EmployeeAttendanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeAttendanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeAttendancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployeeAttendances::route('/'),
            'create' => CreateEmployeeAttendance::route('/create'),
            'view' => ViewEmployeeAttendance::route('/{record}'),
            'edit' => EditEmployeeAttendance::route('/{record}/edit'),
        ];
    }
}
