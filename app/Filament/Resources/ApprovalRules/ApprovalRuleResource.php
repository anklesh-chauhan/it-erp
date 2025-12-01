<?php

namespace App\Filament\Resources\ApprovalRules;

use App\Filament\Resources\ApprovalRules\Pages\CreateApprovalRule;
use App\Filament\Resources\ApprovalRules\Pages\EditApprovalRule;
use App\Filament\Resources\ApprovalRules\Pages\ListApprovalRules;
use App\Filament\Resources\ApprovalRules\Schemas\ApprovalRuleForm;
use App\Filament\Resources\ApprovalRules\Tables\ApprovalRulesTable;
use App\Models\ApprovalRule;
use App\Traits\HasSafeGlobalSearch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApprovalRuleResource extends Resource
{
    use HasSafeGlobalSearch;

    protected static ?string $model = ApprovalRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckCircle;

    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';

    protected static ?string $recordTitleAttribute = 'ApprovalRuleResource';

    public static function form(Schema $schema): Schema
    {
        return ApprovalRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApprovalRulesTable::configure($table);
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
            'index' => ListApprovalRules::route('/'),
            'create' => CreateApprovalRule::route('/create'),
            'edit' => EditApprovalRule::route('/{record}/edit'),
        ];
    }
}
