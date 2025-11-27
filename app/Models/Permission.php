<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class Permission extends SpatiePermission
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = ['name', 'guard_name'];
}
