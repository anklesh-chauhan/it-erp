<?php

namespace App\Models;

use App\Enums\ApprovalActivityAction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalActivity extends BaseModel
{
    protected $fillable = [
        'approval_id',
        'approval_step_id',
        'actor_id',
        'action',
        'from_status',
        'to_status',
        'comments',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'action' => ApprovalActivityAction::class,
            'metadata' => 'array',
        ];
    }

    public function approval(): BelongsTo
    {
        return $this->belongsTo(Approval::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(ApprovalStep::class, 'approval_step_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
