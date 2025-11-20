<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialProjection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'financial_projections';

    protected $fillable = [
        'tenant_id',
        'name',
        'scenario',
        'start_date',
        'end_date',
        'projection_periods',
        'period_type',
        'sales_projection',
        'expenses_projection',
        'cash_flow_projection',
        'growth_rate',
        'assumptions',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sales_projection' => 'array',
        'expenses_projection' => 'array',
        'cash_flow_projection' => 'array',
        'growth_rate' => 'decimal:4',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function projectSales(array $historicalData = []): array
    {
        $projections = [];
        $lastValue = !empty($historicalData) ? end($historicalData) : 0;
        $growthRate = $this->growth_rate / 100;

        for ($i = 0; $i < $this->projection_periods; $i++) {
            if ($this->period_type === 'yearly') {
                $lastValue = $lastValue * (1 + $growthRate);
            } elseif ($this->period_type === 'quarterly') {
                $lastValue = $lastValue * (1 + ($growthRate / 4));
            } else {
                $lastValue = $lastValue * (1 + ($growthRate / 12));
            }
            $projections[] = round($lastValue, 2);
        }

        $this->sales_projection = $projections;
        $this->save();

        return $projections;
    }
}
