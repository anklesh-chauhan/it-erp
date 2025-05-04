<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NumberSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'modelable_id',
        'modelable_type',
        'model_type',
        'Prefix',
        'next_number',
        'Suffix',
        'type_master_id',
    ];

    /**
     * Get the parent modelable model (polymorphic relation).
     */
    public function modelable()
    {
        return $this->morphTo();
    }

    public function typeMaster()
    {
        return $this->belongsTo(TypeMaster::class, 'type_master_id');
    }

    public static function getNextNumber(string $modelClass, $typeMasterId = null): string
    {
        // Fetch or create the number series for the given model and type_master_id
        $numberSeries = self::firstOrCreate(
            [
                'model_type' => $modelClass,
                'type_master_id' => $typeMasterId ?? null, // Allow null for default sequence
            ],
            [
                'next_number' => 1,
                'Prefix' => '', // Can be set dynamically based on type_master_id if needed
                'Suffix' => '',
            ]
        );

        // Optionally set Prefix based on TypeMaster (e.g., 'V-' for Vendor)
        if ($typeMasterId) {
            $typeMaster = TypeMaster::find($typeMasterId);
            if ($typeMaster) {
                $numberSeries->update(['Prefix' => strtoupper(substr($typeMaster->name, 0, 1)) . '-']);
                $numberSeries->save();
            }
        }

        return "{$numberSeries->Prefix}{$numberSeries->next_number}{$numberSeries->Suffix}";
    }

    public static function incrementNextNumber(string $modelClass, $typeMasterId = null): void
    {
        self::where('model_type', $modelClass)
            ->where('type_master_id', $typeMasterId ?? null)
            ->increment('next_number');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Add validation logic here
            if (!is_numeric($model->next_number)) {
                throw new \Exception('The next number must be numeric.');
            }
        });
    }

}
