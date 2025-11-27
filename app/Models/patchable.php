<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

use App\Traits\HasApprovalWorkflow;

class Patchable extends MorphPivot
{
    use HasApprovalWorkflow;

    protected $table = 'patchables';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'patch_id',
        'patchable_id',
        'patchable_type',
        'order',
        'created_at',
        'updated_at', // Include if timestamps are present
    ];

    // Define the parent key to ensure correct mapping
    protected $foreignKey = 'patch_id';

    // Define relationships
    public function patch()
    {
        return $this->belongsTo(Patch::class, 'patch_id');
    }

    public function patchable()
    {
        return $this->morphTo();
    }

    public function patchablePivots() // <-- CORRECT NEW RELATIONSHIP
{
    return $this->hasMany(Patchable::class, 'patch_id');
}
}
