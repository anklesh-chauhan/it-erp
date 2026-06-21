<?php

namespace App\Filament\Resources\SampleIssues\Pages;

use App\Filament\Resources\SampleIssues\SampleIssueResource;
use App\Models\SampleRequest;
use App\Models\SampleRequestLine;
use Filament\Resources\Pages\CreateRecord;

class CreateSampleIssue extends CreateRecord
{
    protected static string $resource = SampleIssueResource::class;

    public function mount(): void
    {
        parent::mount();

        $sampleRequestId = request()->integer('sample_request_id');

        if (! $sampleRequestId) {
            return;
        }

        $sampleRequest = SampleRequest::query()->with('lines.item')->find($sampleRequestId);

        if ($sampleRequest === null) {
            return;
        }

        $this->form->fill([
            'sample_request_id' => $sampleRequest->id,
            'to_location_id' => $sampleRequest->location_master_id,
            'issue_date' => now()->toDateString(),
            'lines' => $sampleRequest->lines
                ->filter(fn (SampleRequestLine $line): bool => $line->remainingApprovedQuantity() > 0)
                ->map(fn (SampleRequestLine $line): array => [
                    'sample_request_line_id' => $line->id,
                    'item_master_id' => $line->item_master_id,
                    'quantity' => $line->remainingApprovedQuantity(),
                    'unit_cost' => $line->item?->purchase_price,
                ])
                ->values()
                ->all(),
        ]);
    }
}
