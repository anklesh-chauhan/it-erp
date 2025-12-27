<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;

class Image extends BaseModel
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
