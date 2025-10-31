<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'financial_period_id',
        'name',
        'department',
        'cost_center',
        'budget_type',
        'total_amount',
        'executed_amount',
        'status',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'executed_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function financialPeriod()
    {
        return $this->belongsTo(FinancialPeriod::class);
    }

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getVarianceAttribute(): float
    {
        return $this->total_amount - $this->executed_amount;
    }

    public function getVariancePercentageAttribute(): float
    {
        if ($this->total_amount == 0) {
            return 0;
        }
        return (($this->total_amount - $this->executed_amount) / $this->total_amount) * 100;
    }

    public function updateExecutedAmount()
    {
        $this->executed_amount = $this->items()->sum('executed_amount');
        $this->save();
    }
}
