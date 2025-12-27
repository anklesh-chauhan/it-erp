<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;

class EmployeeShift extends BaseModel
{
    protected $table = 'employee_shifts_pivot';

    protected $fillable = [
        'employee_id',
        'shift_master_id',
        'effective_from',
        'effective_to',
        'is_current',
    ];

    public function shift()
    {
        return $this->belongsTo(ShiftMaster::class, 'shift_master_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {

            $from = $model->effective_from;
            $to   = $model->effective_to ?? '9999-12-31';

            $overlapExists = static::where('employee_id', $model->employee_id)
                ->where('id', '!=', $model->id)
                ->where(function ($q) use ($from, $to) {
                    $q->whereBetween('effective_from', [$from, $to])
                    ->orWhereBetween('effective_to', [$from, $to])
                    ->orWhere(function ($q) use ($from, $to) {
                        $q->where('effective_from', '<=', $from)
                            ->where(function ($q) use ($to) {
                                $q->whereNull('effective_to')
                                ->orWhere('effective_to', '>=', $to);
                            });
                    });
                })
                ->exists();

            if ($overlapExists) {

                Notification::make()
                    ->danger()
                    ->title('Shift conflict')
                    ->body('Shift dates overlap with an existing shift assignment.')
                    ->send();

                throw ValidationException::withMessages([
                    'effective_from' => 'Shift dates overlap with an existing shift assignment.',
                ]);
            }
        });
    }

}
