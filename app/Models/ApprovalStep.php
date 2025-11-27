<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStep extends Model
{
    protected $table = 'approval_steps';

    protected $fillable = ['approval_id','approver_id','level','status','comments','approved_at'];

    public function approval(): BelongsTo
    {
        return $this->belongsTo(Approval::class);
    }


    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id');
    }
}
