<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampleIssueLine extends BaseModel
{
    /** @use HasFactory<\Database\Factories\SampleIssueLineFactory> */
    use HasFactory;

    protected $fillable = [
        'sample_issue_id',
        'sample_request_line_id',
        'item_master_id',
        'inventory_batch_id',
        'quantity',
        'unit_cost',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_cost' => 'decimal:4',
        ];
    }

    public function sampleIssue(): BelongsTo
    {
        return $this->belongsTo(SampleIssue::class);
    }

    public function sampleRequestLine(): BelongsTo
    {
        return $this->belongsTo(SampleRequestLine::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemMaster::class, 'item_master_id');
    }
}
