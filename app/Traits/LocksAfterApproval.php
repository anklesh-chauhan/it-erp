<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

trait LocksAfterApproval
{
    protected static function bootLocksAfterApproval(): void
    {
        static::updating(function ($model) {

            // If not previously approved → allow
            if ($model->getOriginal('approval_status') !== 'approved') {
                return;
            }

            // HR override permission (model-specific)
            $user = Auth::user();
            $permission = 'OverrideApproval:' . class_basename($model);

            if ($user && $user->can($permission)) {
                static::logApprovalOverride($model, $user);
                return;
            }

            throw ValidationException::withMessages([
                'approval_status' => 'Approved records cannot be modified.',
            ]);
        });

        static::deleting(function ($model) {

            if ($model->approval_status !== 'approved') {
                return;
            }

            $user = Auth::user();
            $permission = 'OverrideApproval:' . class_basename($model);

            if ($user && $user->can($permission)) {
                static::logApprovalOverride($model, $user);
                return;
            }

            throw ValidationException::withMessages([
                'approval_status' => 'Approved records cannot be deleted.',
            ]);
        });
    }

    protected static function logApprovalOverride($model, $user): void
    {
        if (! class_exists(\App\Models\ApprovalOverride::class)) {
            return;
        }

        \App\Models\ApprovalOverride::create([
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'user_id' => $user->id,
            'reason' => request('override_reason') ?? 'HR override',
        ]);
    }
}
