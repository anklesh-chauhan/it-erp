<?php

namespace App\Models;

use App\Models\BaseModel;

class ImageTag extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'industry_type',
        'is_active',
    ];

    public function media()
    {
        return $this->morphedByMany(Media::class, 'taggable');
    }
}
