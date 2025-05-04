<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'description',
    ];

    /**
     * Polymorphic Relation for Images
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
