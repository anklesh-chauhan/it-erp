<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;


class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'followupable_id',
        'followupable_type',
        'user_id',
        'follow_up_date',
        'interaction',
        'outcome',
        'media',
        'result',
        'next_follow_up_date',
        'priority',
        'status',
        'to_whom',
        'follow_up_media_id',
        'follow_up_result_id',
        'follow_up_status_id',
        'follow_up_priority_id',
    ];

    public function followupable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contactDetail()
    {
        return $this->belongsTo(ContactDetail::class, 'to_whom');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(FollowUpMedia::class, 'follow_up_media_id');
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(FollowUpResult::class, 'follow_up_result_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(FollowUpStatus::class, 'follow_up_status_id');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(FollowUpPriority::class, 'follow_up_priority_id');
    }
}
