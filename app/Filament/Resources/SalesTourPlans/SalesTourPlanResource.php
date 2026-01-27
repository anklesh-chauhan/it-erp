<?php

namespace App\Filament\Resources\SalesTourPlans;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Resources\SalesTourPlans\Pages\CreateSalesTourPlan;
use App\Filament\Resources\SalesTourPlans\Pages\EditSalesTourPlan;
use App\Filament\Resources\SalesTourPlans\Pages\ListSalesTourPlans;
use App\Filament\Resources\SalesTourPlans\Schemas\SalesTourPlanForm;
use App\Filament\Resources\SalesTourPlans\Tables\SalesTourPlansTable;
use App\Models\SalesTourPlan;
use BackedEnum;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use App\Traits\HasVisibilityScope;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalesTourPlanResource extends BaseResource
{
    use HasSafeGlobalSearch, HasVisibilityScope;

    protected static ?string $model = SalesTourPlan::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static string | \UnitEnum | null $navigationGroup = 'Marketing & Field Sales';
    // Added a label for better readability in the navigation
    protected static ?string $navigationLabel = 'Sales Tour Plan';

    protected static ?string $recordTitleAttribute = 'SalesTourPlan';

    public function scopeApplyOwnVisibility(Builder $query, $user): Builder
    {
        if (! $user->employee) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('employee_id', $user->employee->id);
    }

    public static function form(Schema $schema): Schema
    {
        return SalesTourPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesTourPlansTable::configure($table);
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
            'index' => ListSalesTourPlans::route('/'),
            'create' => CreateSalesTourPlan::route('/create'),
            'edit' => EditSalesTourPlan::route('/{record}/edit'),
        ];
    }
}
