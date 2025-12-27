<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class TermsAndCondition extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = [
        'model_id',
        'model_type',
        'title',
        'content',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }
}
