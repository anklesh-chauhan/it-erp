<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ExpenseConfiguration;

class SalesDcr extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'user_id',
        'jointwork_user_ids',
        'visit_type_id',
        'tour_plan_id',
        'visit_route_ids',
        'category_id',
        'category_type',
        'expense_total'
    ];

    protected $casts = [
        'jointwork_user_ids' => 'array',
        'visit_route_ids' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tourPlan()
    {
        return $this->belongsTo(TourPlan::class);
    }

    public function expenseConfigurations()
    {
        return ExpenseConfiguration::where('category_id', $this->category_id)
            ->where('mode_of_transport_id', $this->tourPlan->mode_of_transport ?? null)
            ->get();
    }

    // Expense Calculation Logic
    public function calculateExpense()
    {
        $totalExpense = 0;

        // Calculate Transport Expense
        $transportConfig = ExpenseConfiguration::where('expense_type_id', 'Transport')
            ->where('mode_of_transport_id', $this->tourPlan->mode_of_transport ?? null)
            ->first();

        if ($transportConfig && $this->tourPlan) {
            $totalExpense += $this->tourPlan->distance_travelled * $transportConfig->rate_per_km;
        }

        // Add Additional Fixed Expenses
        $fixedExpenses = ExpenseConfiguration::whereNotNull('fixed_expense')->get();

        foreach ($fixedExpenses as $expense) {
            $totalExpense += $expense->fixed_expense;
        }

        $this->expense_total = $totalExpense;
    }

    // Auto-calculate on saving
    protected static function booted()
    {
        static::creating(function ($salesDcr) {
            $salesDcr->calculateExpense();
        });

        static::updating(function ($salesDcr) {
            $salesDcr->calculateExpense();
        });
    }
}
