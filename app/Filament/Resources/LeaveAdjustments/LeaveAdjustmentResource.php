<?php

namespace App\Filament\Resources\LeaveAdjustments;

use App\Filament\Resources\LeaveAdjustments\Pages\CreateLeaveAdjustment;
use App\Filament\Resources\LeaveAdjustments\Pages\EditLeaveAdjustment;
use App\Filament\Resources\LeaveAdjustments\Pages\ListLeaveAdjustments;
use App\Filament\Resources\LeaveAdjustments\Schemas\LeaveAdjustmentForm;
use App\Filament\Resources\LeaveAdjustments\Tables\LeaveAdjustmentsTable;
use App\Models\LeaveAdjustment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveAdjustmentResource extends Resource
{
    protected static ?string $model = LeaveAdjustment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'LeaveAdjustment';

    public static function form(Schema $schema): Schema
    {
        return LeaveAdjustmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveAdjustmentsTable::configure($table);
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
            'index' => ListLeaveAdjustments::route('/'),
            'create' => CreateLeaveAdjustment::route('/create'),
            'edit' => EditLeaveAdjustment::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
