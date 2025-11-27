<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Traits\HasApprovalWorkflow;

class TypeMaster extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['name', 'description', 'typeable_id', 'typeable_type'];

    public function typeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeOfType($query, string $model)
    {
        return $query->where('typeable_type', $model);
    }
}
