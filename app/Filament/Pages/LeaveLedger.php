<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\HR\LeaveManagementCluster;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{
    LeaveInstance,
    LeaveAdjustment,
    LeaveEncashment,
    LeaveLapseRecord,
    LeaveLedgerEntry
};
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class LeaveLedger extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $cluster = LeaveManagementCluster::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationLabel = 'Leave Ledger';
    protected static ?string $title = 'Leave Ledger';
    protected string $view = 'filament.pages.leave-ledger';

    /**
     * Unified Ledger Query (Projection)
     */
    protected function getTableQuery(): Builder
    {
        return LeaveLedgerEntry::query()
            ->fromSub($this->ledgerUnionQuery(), 'leave_ledger')
            ->orderBy('date');
    }

    public static function ledgerUnionQuery()
    {
        return DB::query()
            ->fromSub(

                DB::query()

                    /* ================= Leave Instances ================= */
                    ->from('leave_instances')
                    ->join('users', 'users.id', '=', 'leave_instances.employee_id')
                    ->selectRaw("
                        leave_instances.employee_id,
                        leave_instances.leave_type_id,
                        users.name as employee_name,
                        leave_instances.date,
                        'Leave Applied' as source,
                        -leave_instances.pay_factor as amount
                    ")

                    /* ================= Adjustments ================= */
                    ->unionAll(
                        DB::table('leave_adjustments')
                            ->join('users', 'users.id', '=', 'leave_adjustments.employee_id')
                            ->selectRaw("
                                leave_adjustments.employee_id,
                                leave_adjustments.leave_type_id,
                                users.name as employee_name,
                                leave_adjustments.effective_date as date,
                                leave_adjustments.reason as source,
                                CASE
                                    WHEN leave_adjustments.type = 'positive' THEN leave_adjustments.days
                                    ELSE -leave_adjustments.days
                                END as amount
                            ")
                    )

                    /* ================= Encashments ================= */
                    ->unionAll(
                        DB::table('leave_encashments')
                            ->join('users', 'users.id', '=', 'leave_encashments.employee_id')
                            ->selectRaw("
                                leave_encashments.employee_id,
                                leave_encashments.leave_type_id,
                                users.name as employee_name,
                                leave_encashments.encashed_on as date,
                                'Encashment' as source,
                                -leave_encashments.days as amount
                            ")
                    )

                    /* ================= Lapse Records ================= */
                    ->unionAll(
                        DB::table('leave_lapse_records')
                            ->join('users', 'users.id', '=', 'leave_lapse_records.employee_id')
                            ->selectRaw("
                                leave_lapse_records.employee_id,
                                leave_lapse_records.leave_type_id,
                                users.name as employee_name,
                                leave_lapse_records.lapsed_on as date,
                                'Lapse' as source,
                                -leave_lapse_records.days as amount
                            ")
                    ),

                'raw_ledger'
            )

            /* ================= Synthetic ID ================= */
            ->selectRaw("
                ROW_NUMBER() OVER (ORDER BY date, source) as id,
                employee_id,
                leave_type_id,
                employee_name,
                date,
                source,
                amount
            ");
    }


    /**
     * Ledger Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('source')
                    ->label('Transaction'),

                Tables\Columns\TextColumn::make('amount')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
            ])
            ->defaultSort('date', 'asc')
            ->paginated(true);
    }


}
