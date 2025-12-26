<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBlameable;
use App\Traits\HasSoftDeleteBlameable;

abstract class BaseModel extends Model
{
    use HasBlameable;
    use HasSoftDeleteBlameable;
}
