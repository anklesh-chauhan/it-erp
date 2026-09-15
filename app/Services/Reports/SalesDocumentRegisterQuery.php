<?php

namespace App\Services\Reports;

use App\Models\Quote;
use App\Models\SalesDocumentRegisterRow;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesDocumentRegisterQuery
{
    public function builder(): Builder
    {
        $quotes = Quote::query()->select($this->documentColumns('quotes', 'quote'));
        $orders = SalesOrder::query()->select($this->documentColumns('sales_orders', 'sales_order'));
        $invoices = SalesInvoice::query()->select($this->documentColumns('sales_invoices', 'sales_invoice'));

        $query = SalesDocumentRegisterRow::query()
            ->fromSub($quotes->unionAll($orders)->unionAll($invoices), 'sales_document_register_rows')
            ->with(['salesPerson', 'accountMaster']);

        $user = Auth::user();

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if (
            $user->hasRole('super_admin') ||
            $user->hasRole('administration_admin') ||
            $user->can('AccessAllRecords')
        ) {
            return $query;
        }

        return $query->where(function (Builder $visibilityQuery) use ($user): void {
            $visibilityQuery
                ->where('created_by', $user->id)
                ->orWhere('sales_person_id', $user->id);
        });
    }

    /**
     * @return list<mixed>
     */
    protected function documentColumns(string $table, string $type): array
    {
        return [
            DB::raw("CONCAT('{$type}-', {$table}.id) as id"),
            DB::raw("'{$type}' as document_type"),
            DB::raw("{$table}.id as source_id"),
            "{$table}.document_number",
            "{$table}.date",
            "{$table}.status",
            "{$table}.total",
            "{$table}.sales_person_id",
            "{$table}.account_master_id",
            "{$table}.created_by",
        ];
    }
}
