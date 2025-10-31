<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'chart_account_id',
        'description',
        'budgeted_amount',
        'executed_amount',
        'month',
        'notes',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'executed_amount' => 'decimal:2',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function getVarianceAttribute(): float
    {
        return $this->budgeted_amount - $this->executed_amount;
    }

    public function getVariancePercentageAttribute(): float
    {
        if ($this->budgeted_amount == 0) {
            return 0;
        }
        return (($this->budgeted_amount - $this->executed_amount) / $this->budgeted_amount) * 100;
    }
}
