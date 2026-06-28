<?php

namespace App\Services\Marketing;

use App\Enums\PromotionalSchemeAppliesTo;
use App\Enums\PromotionalSchemeStatus;
use App\Models\PromotionalScheme;
use App\Models\SalesDocument;
use Illuminate\Support\Collection;

class PromotionalSchemeService
{
    /**
     * Resolve active promotional schemes applicable to a sales document.
     *
     * @return Collection<int, PromotionalScheme>
     */
    public function resolveApplicableSchemes(SalesDocument $document): Collection
    {
        $documentDate = $document->date ?? now();
        $accountId = $document->account_master_id ?? null;
        $territoryId = $document->territory_id ?? null;

        return PromotionalScheme::query()
            ->where('status', PromotionalSchemeStatus::Active)
            ->where(function ($query) use ($documentDate): void {
                $query->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', $documentDate);
            })
            ->where(function ($query) use ($documentDate): void {
                $query->whereNull('valid_to')
                    ->orWhere('valid_to', '>=', $documentDate);
            })
            ->where(function ($query) use ($accountId, $territoryId): void {
                $query->where('applies_to', PromotionalSchemeAppliesTo::Global);

                if ($accountId !== null) {
                    $query->orWhere(function ($query) use ($accountId): void {
                        $query->where('applies_to', PromotionalSchemeAppliesTo::Customer)
                            ->where('applies_to_id', $accountId);
                    });
                }

                if ($territoryId !== null) {
                    $query->orWhere(function ($query) use ($territoryId): void {
                        $query->where('applies_to', PromotionalSchemeAppliesTo::Territory)
                            ->where('applies_to_id', $territoryId);
                    });
                }
            })
            ->with('benefits')
            ->get()
            ->filter(function (PromotionalScheme $scheme) use ($document): bool {
                if ($scheme->min_order_value === null) {
                    return true;
                }

                return (float) ($document->total ?? 0) >= (float) $scheme->min_order_value;
            });
    }

    /**
     * Calculate structured promotional benefits beyond line-level discounts.
     *
     * @return array<int, array{scheme: PromotionalScheme, benefits: array<int, array<string, mixed>>}>
     */
    public function calculateBenefits(SalesDocument $document): array
    {
        $results = [];

        foreach ($this->resolveApplicableSchemes($document) as $scheme) {
            $benefits = [];

            foreach ($scheme->benefits as $benefit) {
                $benefits[] = [
                    'type' => $benefit->benefit_type->value,
                    'item_id' => $benefit->item_master_id,
                    'buy_quantity' => $benefit->buy_quantity,
                    'get_quantity' => $benefit->get_quantity,
                    'discount_value' => $benefit->discount_value,
                    'min_quantity' => $benefit->min_quantity,
                    'max_quantity' => $benefit->max_quantity,
                ];
            }

            $results[] = [
                'scheme' => $scheme,
                'benefits' => $benefits,
            ];
        }

        return $results;
    }
}
