<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;

class Attachment extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'description',
        'attachable_id', // Add this for polymorphic relation
        'attachable_type', // Add this for polymorphic relation
    ];

    /**
     * Polymorphic Relation for Attachments
     */
    public function attachable()
    {
        return $this->morphTo();
    }
}
