<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;

class LeadActivity extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['lead_id', 'user_id', 'activity_type', 'description'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
