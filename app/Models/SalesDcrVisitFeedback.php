<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesDcrVisitFeedback extends BaseModel
{
    protected $fillable = [
        'sales_dcr_visit_id',
        'visit_feedback_question_id',
        'answer',
        'remarks',
    ];

    // SalesDcrVisitFeedback
    public function question()
    {
        return $this->belongsTo(
            VisitFeedbackQuestion::class,
            'visit_feedback_question_id'
        );
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(SalesDcrVisit::class, 'sales_dcr_visit_id');
    }

}
