<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitFeedback extends BaseModel
{
    protected $table = 'visit_feedbacks';

    protected $fillable = [
        'visit_id',
        'visit_feedback_question_id',
        'answer',
        'remarks',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            VisitFeedbackQuestion::class,
            'visit_feedback_question_id'
        );
    }
}
