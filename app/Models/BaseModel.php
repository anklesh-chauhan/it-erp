<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasBlameable;
use App\Traits\HasSoftDeleteBlameable;
use App\Traits\HasVisibilityScope;

abstract class BaseModel extends Model
{
    use SoftDeletes;
    use HasBlameable;
    use HasSoftDeleteBlameable;
    use HasVisibilityScope;

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
}
