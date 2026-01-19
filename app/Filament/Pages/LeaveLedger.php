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
    LeaveLapseRecord
};

class LeaveLedger extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $cluster = LeaveManagementCluster::class;
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationLabel = 'Leave Ledger';
    protected static ?string $title = 'Leave Ledger';
    protected string $view = 'filament.pages.leave-ledger';

    protected function getTableQuery(): Builder
    {
        return LeaveInstance::query()
            ->selectRaw("
                date,
                'Leave Applied' as source,
                -pay_factor as amount
            ")
            ->unionAll(
                LeaveAdjustment::query()->selectRaw("
                    adjustment_date as date,
                    reason as source,
                    amount
                ")
            )
            ->unionAll(
                LeaveEncashment::query()->selectRaw("
                    encashment_date as date,
                    'Encashment' as source,
                    -days as amount
                ")
            )
            ->unionAll(
                LeaveLapseRecord::query()->selectRaw("
                    lapse_date as date,
                    'Lapse' as source,
                    -days as amount
                ")
            )
            ->orderBy('date');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('date')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('source')
                ->label('Transaction'),

            Tables\Columns\TextColumn::make('amount')
                ->badge()
                ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
        ];
    }
}
