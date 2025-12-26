<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasSoftDeleteBlameable
{
    protected static function bootHasSoftDeleteBlameable(): void
    {
        static::deleting(function ($model) {
            if ($model->isForceDeleting()) {
                return;
            }

            if (Auth::check()) {
                $model->deleted_by = Auth::id();
                $model->saveQuietly();
            }
        });

        static::restoring(function ($model) {
            $model->deleted_by = null;
        });
    }

    /* ---------- Relationships ---------- */

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }
}
