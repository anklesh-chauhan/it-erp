<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

use App\Traits\HasApprovalWorkflow;

class LeadNote extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['lead_id', 'user_id', 'note'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leadNote) {
            if (!$leadNote->user_id) {
                $leadNote->user_id = Auth::id(); // Automatically set the logged-in user
            }
        });
    }
}
