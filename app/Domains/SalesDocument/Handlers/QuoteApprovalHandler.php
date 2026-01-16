<?php

namespace App\Domains\SalesDocument\Handlers;

use App\Models\Approval;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Model;
use LogicException;
use App\Domains\Approval\Contracts\ApprovalHandler;

class QuoteApprovalHandler implements ApprovalHandler
{
    public static function supports(): string
    {
        return Quote::class;
    }

    /**
     * Handle approval outcome for Quote.
     */
    public function handle(Model $model, Approval $approval): void
    {
        if (! $model instanceof Quote) {
            throw new LogicException(
                'QuoteApprovalHandler received invalid model: ' . $model::class
            );
        }

        match ($approval->approval_status) {
            'approved' => $this->approve($model),
            'rejected' => $this->reject($model),
            default    => null, // draft / pending → no action
        };
    }

    protected function approve(Quote $quote): void
    {
        $quote->update([
            'approval_status' => 'approved',
        ]);

        // Future hooks:
        // - lock pricing
        // - allow conversion to order
        // - notify sales team
    }

    protected function reject(Quote $quote): void
    {
        $quote->update([
            'approval_status' => 'rejected',
        ]);

        // Future hooks:
        // - notify requester
        // - log rejection reason
    }
}
