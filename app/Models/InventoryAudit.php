<?php

namespace App\Models;

use App\Enums\InventoryDocumentStatus;
use App\Services\Inventory\InventoryService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class InventoryAudit extends BaseModel
{
    protected $fillable = [
        'audit_number',
        'location_master_id',
        'audit_date',
        'status',
        'remarks',
        'posted_by',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'audit_date' => 'date',
            'status' => InventoryDocumentStatus::class,
            'posted_at' => 'datetime',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(LocationMaster::class, 'location_master_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InventoryAuditLine::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(InventoryMovement::class, 'reference');
    }

    public function isPosted(): bool
    {
        return $this->posted_at !== null;
    }

    public function isEditable(): bool
    {
        return $this->status === InventoryDocumentStatus::Draft && ! $this->isPosted();
    }

    public function post(): void
    {
        app(InventoryService::class)->postAudit($this, Auth::id());
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (InventoryAudit $audit): void {
            $audit->audit_number ??= NumberSeries::getNextNumber(InventoryAudit::class);
            $audit->audit_date ??= now()->toDateString();
            $audit->status ??= InventoryDocumentStatus::Draft;
        });

        static::created(function (InventoryAudit $audit): void {
            NumberSeries::incrementNextNumber(InventoryAudit::class);
        });
    }
}
