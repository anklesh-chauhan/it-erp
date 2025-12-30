<?php

namespace App\Filament\Resources;

use App\Traits\HasSafeGlobalSearch;

use Filament\Resources\Resource;
use App\Filament\Resources\Concerns\HasSendForApprovalAction;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseResource extends Resource
{
    use HasSafeGlobalSearch;
    use HasSendForApprovalAction;

    protected static function permissionKey(): string
    {
        return str_replace('Resource', '', class_basename(static::class));
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->applyVisibility(static::permissionKey());

    }
}
