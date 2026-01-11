<?php

namespace App\Domains\SalesDocument\Handlers;

use App\Models\Quote;

class QuoteApprovalHandler
{
    public function handle(Quote $quote, string $status): void
    {
        if ($status === 'approved') {
            $quote->update(['approval_status' => 'approved']);
        }

        if ($status === 'rejected') {
            $quote->update(['approval_status' => 'rejected']);
        }
    }
}
