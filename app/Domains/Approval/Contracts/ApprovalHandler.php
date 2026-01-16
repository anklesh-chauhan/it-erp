<?php

namespace App\Domains\Approval\Contracts;

use App\Models\Approval;
use Illuminate\Database\Eloquent\Model;

interface ApprovalHandler
{
    /**
     * Return the fully-qualified model class this handler supports.
     */
    public static function supports(): string;

    /**
     * Handle approval outcome for a domain model.
     *
     * @param Model    $model     The approvable model (Quote, Leave, etc.)
     * @param Approval $approval  The approval record with final status
     */
    public function handle(Model $model, Approval $approval): void;
}
