<?php

namespace App\Jobs;

use App\Enums\ApprovalActivityAction;
use App\Models\ApprovalActivity;
use App\Models\ApprovalStep;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EscalateOverdueApprovals implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ApprovalStep::query()
            ->with('approval')
            ->where('status', 'pending')
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->whereNull('escalated_at')
            ->chunkById(100, function ($steps): void {
                foreach ($steps as $step) {
                    $step->forceFill(['escalated_at' => now()])->save();

                    ApprovalActivity::create([
                        'approval_id' => $step->approval_id,
                        'approval_step_id' => $step->id,
                        'action' => ApprovalActivityAction::Escalated,
                        'from_status' => 'pending',
                        'to_status' => 'pending',
                        'metadata' => [
                            'due_at' => $step->due_at?->toISOString(),
                        ],
                    ]);
                }
            });
    }
}
