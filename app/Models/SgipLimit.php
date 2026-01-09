<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SgipLimit extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'applies_to',      // account | employee | territory | global
        'applies_to_id',
        'item_type',       // sample | gift | input
        'period',          // daily | monthly | yearly
        'max_quantity',
        'max_value',
    ];

    protected $casts = [
        'max_quantity' => 'integer',
        'max_value'    => 'decimal:2',
    ];

    /* ============================
     | Helpers
     ============================ */

    public function isGlobal(): bool
    {
        return $this->applies_to === 'global';
    }

    protected static function booted(): void
    {
        static::saving(function ($limit) {
            $exists = self::query()
                ->where('id', '!=', $limit->id)
                ->where('applies_to', $limit->applies_to)
                ->where('applies_to_id', $limit->applies_to_id)
                ->where('item_type', $limit->item_type)
                ->where('period', $limit->period)
                ->exists();

            if ($exists) {
                throw new \InvalidArgumentException(
                    'A limit already exists for this combination.'
                );
            }
        });
    }
}
