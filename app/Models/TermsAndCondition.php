<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class TermsAndCondition extends Model
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
