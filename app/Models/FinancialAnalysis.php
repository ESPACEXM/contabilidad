<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialAnalysis extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'analysis_type',
        'initial_investment',
        'discount_rate',
        'cash_flows',
        'result_value',
        'fixed_costs',
        'variable_cost_per_unit',
        'selling_price_per_unit',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'initial_investment' => 'decimal:2',
        'discount_rate' => 'decimal:4',
        'result_value' => 'decimal:2',
        'cash_flows' => 'array',
        'fixed_costs' => 'decimal:2',
        'variable_cost_per_unit' => 'decimal:2',
        'selling_price_per_unit' => 'decimal:2',
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

    // Cálculo de VAN (Valor Actual Neto)
    public function calculateNPV(): float
    {
        if ($this->analysis_type !== 'van') {
            return 0;
        }

        $npv = -$this->initial_investment;
        $cashFlows = $this->cash_flows ?? [];
        $rate = $this->discount_rate / 100;

        foreach ($cashFlows as $period => $cashFlow) {
            $npv += $cashFlow / pow(1 + $rate, $period + 1);
        }

        $this->result_value = $npv;
        $this->save();

        return $npv;
    }

    // Cálculo de TIR (Tasa Interna de Retorno)
    public function calculateIRR(): float
    {
        if ($this->analysis_type !== 'tir') {
            return 0;
        }

        $cashFlows = [-$this->initial_investment, ...($this->cash_flows ?? [])];
        $tolerance = 0.0001;
        $maxIterations = 100;
        $rate = 0.1; // Tasa inicial

        for ($i = 0; $i < $maxIterations; $i++) {
            $npv = 0;
            $npvDerivative = 0;

            foreach ($cashFlows as $period => $cashFlow) {
                $factor = pow(1 + $rate, $period);
                $npv += $cashFlow / $factor;
                if ($period > 0) {
                    $npvDerivative -= $period * $cashFlow / ($factor * (1 + $rate));
                }
            }

            if (abs($npv) < $tolerance) {
                break;
            }

            if ($npvDerivative == 0) {
                break;
            }

            $rate = $rate - $npv / $npvDerivative;
        }

        $this->result_value = $rate * 100;
        $this->save();

        return $rate * 100;
    }

    // Cálculo de Punto de Equilibrio
    public function calculateBreakEven(): array
    {
        if ($this->analysis_type !== 'break_even') {
            return ['units' => 0, 'amount' => 0];
        }

        $contributionMargin = $this->selling_price_per_unit - $this->variable_cost_per_unit;
        
        if ($contributionMargin <= 0) {
            return ['units' => 0, 'amount' => 0];
        }

        $units = $this->fixed_costs / $contributionMargin;
        $amount = $units * $this->selling_price_per_unit;

        return [
            'units' => $units,
            'amount' => $amount,
        ];
    }

    // Cálculo de Periodo de Recuperación
    public function calculatePaybackPeriod(): float
    {
        if ($this->analysis_type !== 'payback') {
            return 0;
        }

        $cashFlows = $this->cash_flows ?? [];
        $cumulativeCashFlow = -$this->initial_investment;
        $period = 0;

        foreach ($cashFlows as $cashFlow) {
            $cumulativeCashFlow += $cashFlow;
            $period++;

            if ($cumulativeCashFlow >= 0) {
                break;
            }
        }

        return $period;
    }

    // Índice de Rentabilidad
    public function calculateProfitabilityIndex(): float
    {
        if ($this->analysis_type !== 'profitability_index') {
            return 0;
        }

        $presentValue = 0;
        $cashFlows = $this->cash_flows ?? [];
        $rate = $this->discount_rate / 100;

        foreach ($cashFlows as $period => $cashFlow) {
            $presentValue += $cashFlow / pow(1 + $rate, $period + 1);
        }

        if ($this->initial_investment == 0) {
            return 0;
        }

        return $presentValue / $this->initial_investment;
    }
}
