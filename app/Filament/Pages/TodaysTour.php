<?php

namespace App\Filament\Pages;

use App\Models\AccountMaster;
use App\Models\SalesTourPlanDetail;
use App\Models\Visit;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TodaysTour extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Today’s Visits';

    protected string $view = 'filament.pages.todays-tour';

    /**
     * Collection of SalesTourPlanDetail for today
     */
    public Collection $todayPlans;

    public function mount(): void
    {
        $this->todayPlans = SalesTourPlanDetail::query()
            ->whereDate('date', today())
            ->whereHas('tourPlan', function ($q) {
                $q->where('user_id', Auth::id());
                // 🔓 approval disabled temporarily for testing
                // $q->approved();
            })
            ->with(['territory',
                    'tourPlan',
            ])
            ->get();
    }

    /**
     * Start or open a Visit for a customer
     */
    public function openVisit(
        int $salesTourPlanDetailId,
        int $salesTourPlanId,
        int $territoryId,
        int $patchId,
        int $companyId
    ) {
        // 1️⃣ Try to find existing Visit (STRICT & SAFE)
        $visit = Visit::query()
            ->where('employee_id', Auth::id())
            ->where('sales_tour_plan_detail_id', $salesTourPlanDetailId)
            ->where('patch_id', $patchId)
            ->whereHas('visitables', function ($q) use ($companyId) {
                $q->where('visitable_type', AccountMaster::class)
                  ->where('visitable_id', $companyId);
            })
            ->first();

        // 2️⃣ Create Visit if not exists
        if (! $visit) {
            $visit = Visit::create([
                'document_number'              => 'VIS-' . str_pad(
                    (Visit::max('id') ?? 0) + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),
                'employee_id'                 => Auth::id(),
                'reporting_manager_id'        => Auth::user()->reporting_manager_id,

                'sales_tour_plan_id'          => $salesTourPlanId,
                'sales_tour_plan_detail_id'   => $salesTourPlanDetailId,

                'territory_id'                => $territoryId,
                'patch_id'                    => $patchId,

                'visit_date'                  => today(),
                'visit_type'                  => 'planned',
                'visit_status'                => 'draft',
                'approval_status'             => 'pending',
            ]);

            // 3️⃣ Attach visited company (polymorphic)
            $visit->visitables()->create([
                'visitable_type' => AccountMaster::class,
                'visitable_id'   => $companyId,
            ]);
        }

        // 4️⃣ Redirect to Visit edit page
        return redirect()->route(
            'filament.admin.resources.visits.edit',
            $visit
        );
    }
}
