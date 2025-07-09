<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Patchable extends MorphPivot
{
    protected $table = 'patchables';

    protected $fillable = [
        'patch_id',
        'patchable_id',
        'patchable_type',
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
}