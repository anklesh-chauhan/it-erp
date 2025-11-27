<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class Role extends SpatieRole
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = ['name', 'guard_name'];
}
