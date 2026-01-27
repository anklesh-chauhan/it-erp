<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasBlameable;
use App\Traits\HasSoftDeleteBlameable;
use App\Traits\HasVisibilityScope;
use App\Traits\LocksAfterApproval;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseModel extends Model
{
    use SoftDeletes;
    use HasBlameable;
    use HasSoftDeleteBlameable;
    use HasVisibilityScope;
    use LocksAfterApproval;

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /* =====================================================
     | VISIBILITY HOOKS (DEFAULT: deny)
     | Models override what they support
     ===================================================== */

    public function scopeApplyOwnVisibility(Builder $query, $user): Builder
    {
        // Default: deny
        return $query->whereRaw('1 = 0');
    }

    public function scopeApplyTerritoryVisibility(Builder $query, array $territoryIds): Builder
    {
        // Default: deny
        return $query->whereRaw('1 = 0');
    }

    public function scopeApplyOuVisibility(Builder $query, array $ouIds): Builder
    {
        // Default: deny
        return $query->whereRaw('1 = 0');
    }
}
