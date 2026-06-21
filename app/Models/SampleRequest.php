<?php

namespace App\Models;

use App\Enums\SampleRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SampleRequest extends BaseModel
{
    /** @use HasFactory<\Database\Factories\SampleRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'document_number',
        'employee_id',
        'territory_id',
        'location_master_id',
        'request_date',
        'status',
        'purpose',
        'campaign_id',
    ];

    protected function casts(): array
    {
        return [
            'request_date' => 'date',
            'status' => SampleRequestStatus::class,
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(LocationMaster::class, 'location_master_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(SampleRequestLine::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(SampleIssue::class);
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [
            SampleRequestStatus::Draft,
            SampleRequestStatus::Submitted,
        ], true);
    }

    public function refreshIssueStatus(): void
    {
        $this->loadMissing('lines');

        $approved = (float) $this->lines->sum('quantity_approved');
        $issued = (float) $this->lines->sum('quantity_issued');

        $status = match (true) {
            $approved > 0 && $issued >= $approved => SampleRequestStatus::Fulfilled,
            $issued > 0 => SampleRequestStatus::PartiallyIssued,
            default => SampleRequestStatus::Approved,
        };

        $this->forceFill(['status' => $status])->save();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (SampleRequest $sampleRequest): void {
            $sampleRequest->document_number ??= NumberSeries::getNextNumber(SampleRequest::class);
            $sampleRequest->status ??= SampleRequestStatus::Draft;
        });

        static::created(function (): void {
            NumberSeries::incrementNextNumber(SampleRequest::class);
        });
    }
}
