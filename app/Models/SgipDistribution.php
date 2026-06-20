<?php

namespace App\Models;

use App\Services\SGIPComplianceService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SgipDistribution extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'account_master_id', // Doctor
        'territory_id',
        'sales_tour_plan_id',
        'visit_id',
        'visit_date',
        'total_value',
        'approval_status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'total_value' => 'decimal:2',
    ];

    /* ============================
     | Relationships
     ============================ */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Doctor
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(AccountMaster::class, 'account_master_id');
    }

    public function accountMaster()
    {
        return $this->belongsTo(AccountMaster::class);
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function tourPlan(): BelongsTo
    {
        return $this->belongsTo(SalesTourPlan::class, 'sales_tour_plan_id');
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SgipDistributionItem::class);
    }

    public function violations(): HasMany
    {
        return $this->hasMany(SgipViolation::class);
    }

    /* ============================
     | Helpers
     ============================ */

    public function recalculateTotal(): void
    {
        $this->total_value = $this->items()->sum('total_value');
        $this->saveQuietly();
    }

    protected static function booted(): void
    {
        static::saving(function (SgipDistribution $distribution) {

            // Only validate if date & items exist
            if (! $distribution->visit_date || ! $distribution->exists) {
                return;
            }

            // ❌ Do NOT block draft saves
            if ($distribution->approval_status === 'draft') {
                SGIPComplianceService::validate($distribution, false);
            }
        });
    }
}
