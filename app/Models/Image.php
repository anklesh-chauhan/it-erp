<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class Image extends Model
{
    use HasFactory, HasApprovalWorkflow;

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
