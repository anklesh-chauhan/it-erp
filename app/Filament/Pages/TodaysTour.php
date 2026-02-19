<?php

namespace App\Filament\Pages;

use App\Models\AccountMaster;
use App\Models\SalesTourPlanDetail;
use App\Models\Visit;
use App\Traits\HasVisitManagement;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class TodaysTour extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions, HasVisitManagement;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Today’s Visits';

    protected string $view = 'filament.pages.todays-tour';

    /**
     * Collection of SalesTourPlanDetail for today
     */
    public Collection $todayPlans;

    // Search property
    public ?string $search = '';

    public function mount(): void
    {
        $this->loadPlans();
    }

    public function loadPlans(): void
    {
        $this->todayPlans = SalesTourPlanDetail::query()
            ->whereDate('date', today())
            ->whereHas('tourPlan', fn($q) => $q->where('user_id', Auth::id()))
            ->with([
                'territory',
                'tourPlan',
                'patches.companies' => function ($query) {
                    // If searching, filter the companies right in the SQL query
                    if (filled($this->search)) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    }
                    $query->with(['typeMaster', 'contactDetails']);
                }
            ])
            ->get();
    }

    // Trigger search update
    public function updatedSearch(): void
    {
        $this->loadPlans();
    }

    // The status of a specific company
    public function getVisitStatus(int $companyId, int $detailId, int $patchId): string
    {
        $visit = Visit::query()
            ->where('employee_id', Auth::id())
            ->where('sales_tour_plan_detail_id', $detailId)
            ->where('patch_id', $patchId)
            ->whereHas('visitables', fn ($q) =>
                $q->where('visitable_type', AccountMaster::class)
                ->where('visitable_id', $companyId)
            )
            ->first();

        if (! $visit) {
            return 'planned';
        }

        if ($visit->visit_status === 'cancelled' && $visit->reschedule_state !== 'none') {
            return 'rescheduled';
        }

        return $visit->visit_status;
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
        // using the trait to ensure visit exists or create a new one
        $visit = $this->ensureVisitExists($salesTourPlanDetailId, $patchId, $companyId);

        // Redirect to Visit edit page
        return redirect()->route(
            'filament.admin.resources.visits.edit',
            $visit
        );
    }

    public function cancelAction(): Action
    {
        return Action::make('cancel')
            ->label('Cancel')
            ->icon('heroicon-m-x-mark')
            ->color('danger')
            ->outlined()
            ->size('sm')
            ->form([
                Textarea::make('cancel_reason')
                    ->label('Cancellation Reason')
                    ->required(),
            ])
            ->action(function (array $data, array $arguments) {

                // Use the trait!
                $visit = $this->ensureVisitExists(
                    $arguments['detail_id'],
                    $arguments['patch_id'],
                    $arguments['company_id'],
                    $arguments['visit_id'] ?? null
                );

                $visit->update([
                    'visit_status'     => 'cancelled',
                    'reschedule_state' => 'none',
                    'cancel_reason'    => $data['cancel_reason'],
                ]);

                Notification::make()
                    ->title('Visit Cancelled')
                    ->danger()
                    ->send();

                $this->loadPlans();
            });
    }


    public function rescheduleAction(): Action
    {
        return Action::make('reschedule')
            ->label('Reschedule')
            ->color('info')
            ->icon('heroicon-m-calendar-days')
            ->form([
                DatePicker::make('rescheduled_for') // 👈 Ensure this matches $data key
                    ->label('Proposed New Date')
                    ->required()
                    ->native(false)
                    ->minDate(now()->addDay()) // Cannot reschedule to today or the past
                    ->displayFormat('d/m/Y'),

                Textarea::make('cancel_reason')
                    ->label('Reason for Rescheduling')
                    ->placeholder('Why are we moving this visit?')
                    ->required(),
            ])
            ->action(function (array $data, array $arguments) {
                // Use the trait!
                $visit = $this->ensureVisitExists(
                    $arguments['detail_id'],
                    $arguments['patch_id'],
                    $arguments['company_id'],
                    $arguments['visit_id'] ?? null
                );

                // 2. Perform the update with safety checks
                $visit->update([
                    'visit_status'     => 'cancelled',
                    'reschedule_state' => 'requested',
                    'rescheduled_for'  => $data['rescheduled_for'] ?? null, // Use null-coalescing
                    'cancel_reason'    => $data['cancel_reason'] ?? 'No reason provided',
                ]);

                Notification::make()
                    ->title('Reschedule Requested')
                    ->info()
                    ->send();

                $this->loadPlans();
            });
    }

}
