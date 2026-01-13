<?php

namespace App\Filament\Resources;

use App\Traits\HasSafeGlobalSearch;

use Filament\Resources\Resource;
use App\Filament\Resources\Concerns\HasSendForApprovalAction;
use App\Filament\Resources\Concerns\HasApprovalLockUI;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResource extends Resource
{
    use HasSafeGlobalSearch;
    use HasSendForApprovalAction;
    use HasApprovalLockUI;

    protected static function permissionKey(): string
    {
        return str_replace('Resource', '', class_basename(static::class));
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->applyVisibility(static::permissionKey());

    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // 🔒 Approved record → only HR override can edit
        if ($record->approval_status === 'approved') {
            return $user->can('OverrideApproval:LeaveApplication');
        }

        // ⛔ Not approved → normal update permission
        return $user->can('Update:LeaveApplication');
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // 🔒 Approved record → only HR override can delete
        if ($record->approval_status === 'approved') {
            return $user->can('OverrideApproval:LeaveApplication');
        }

        // ⛔ Not approved → normal delete permission
        return $user->can('Delete:LeaveApplication');
    }
}
