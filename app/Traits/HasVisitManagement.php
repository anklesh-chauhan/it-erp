<?php

namespace App\Traits;

use App\Models\AccountMaster;
use App\Models\SalesTourPlanDetail;
use App\Models\Visit;
use App\Models\VisitFeedbackQuestion;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasVisitManagement
{
    /**
     * Finds or creates a Visit and initializes feedback questions.
     */
    public function ensureVisitExists(
        int $detailId,
        int $patchId,
        int $companyId,
        ?int $visitId = null
    ): Visit {
        // 1. Return if already exists
        if ($visitId && $visit = Visit::find($visitId)) {
            return $visit;
        }

        $existingVisit = Visit::query()
            ->where('employee_id', Auth::id())
            ->where('sales_tour_plan_detail_id', $detailId)
            ->where('patch_id', $patchId)
            ->whereHas('visitables', fn ($q) => $q->where('visitable_id', $companyId))
            ->first();

        if ($existingVisit) {
            return $existingVisit;
        }

        // 2. Create Visit and Feedbacks inside a Transaction
        return DB::transaction(function () use ($detailId, $patchId, $companyId) {
            $detail = SalesTourPlanDetail::findOrFail($detailId);

            // Generate Document Number
            $latestId = Visit::max('id') ?? 0;
            $docNum = 'VIS-' . str_pad($latestId + 1, 6, '0', STR_PAD_LEFT);

            $visit = Visit::create([
                'document_number'            => $docNum,
                'employee_id'               => Auth::id(),
                'reporting_manager_id'      => Auth::user()->reporting_manager_id,
                'sales_tour_plan_id'        => $detail->sales_tour_plan_id,
                'sales_tour_plan_detail_id' => $detail->id,
                'territory_id'              => $detail->territory_id,
                'patch_id'                  => $patchId,
                'visit_date'                => today(),
                'visit_type'                => 'planned',
                'visit_status'              => 'draft',
                'approval_status'           => 'pending',
                'remarks'                   => $detail->remarks,
                'is_joint_work'             => ! empty($detail->joint_with),
            ]);

            // Attach Polymorphic Company
            $visit->visitables()->create([
                'visitable_type' => AccountMaster::class,
                'visitable_id'   => $companyId,
            ]);

            if (! empty($detail->joint_with)) {
                $visit->jointUsers()->sync($detail->joint_with);
            }

            if (! empty($detail->visit_purpose_ids)) {
                $visit->visitPurposes()->sync($detail->visit_purpose_ids);
            }

            // Initialize Feedback Questions
            $this->initializeVisitFeedbacks($visit);

            return $visit->fresh();
        });
    }

    /**
     * Seed feedback questions for a visit (idempotent).
     */
    protected function initializeVisitFeedbacks(Visit $visit): void
    {
        $questions = VisitFeedbackQuestion::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($questions as $question) {
            $visit->feedbacks()->firstOrCreate(
                ['visit_feedback_question_id' => $question->id],
                [
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]
            );
        }
    }

    /**
     * Expected $feedbackData format:
     * [
     *   question_id => [
     *       'answer'  => 3,
     *       'remarks' => 'Customer was interested',
     *   ],
     * ]
     */
    public function updateVisitFeedbacks(Visit $visit, array $feedbackData): void
    {
        foreach ($feedbackData as $row) {

            // Each $row is one repeater item
            if (
                ! isset($row['visit_feedback_question_id']) ||
                ! is_numeric($row['visit_feedback_question_id'])
            ) {
                continue;
            }

            $questionId = (int) $row['visit_feedback_question_id'];

            $visit->feedbacks()
                ->where('visit_feedback_question_id', $questionId)
                ->update([
                    'answer'     => $row['answer'] ?? null,
                    'remarks'    => $row['remarks'] ?? null,
                    'updated_by' => Auth::id(),
                ]);
        }
    }
}
