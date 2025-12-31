<?php

namespace App\Filament\Resources\Approvals;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Resources\Approvals\Pages\CreateApproval;
use App\Filament\Resources\Approvals\Pages\EditApproval;
use App\Filament\Resources\Approvals\Pages\ListApprovals;
use App\Filament\Resources\Approvals\Pages\ViewApproval;
use App\Filament\Resources\Approvals\Schemas\ApprovalForm;
use App\Filament\Resources\Approvals\Schemas\ApprovalInfolist;
use App\Filament\Resources\Approvals\Tables\ApprovalsTable;
use App\Models\Approval;
use BackedEnum;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ApprovalResource extends BaseResource
{
    use HasSafeGlobalSearch;

    protected static ?string $model = Approval::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ApprovalResource';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::pendingForUser()->count();
    }


    public static function form(Schema $schema): Schema
    {
        return ApprovalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApprovalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApprovalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false; // disables create page & button
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApprovals::route('/'),
            // 'create' => CreateApproval::route('/create'),
            'view' => ViewApproval::route('/{record}'),
            'edit' => EditApproval::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('steps', function ($q) {
                $q->where('approver_id', Auth::id());
            });
    }
}
