<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\ChartAccount;
use Carbon\Carbon;

class FinancialStatementService
{
    protected $tenant;
    protected $startDate;
    protected $endDate;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function setPeriod(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getBalanceSheet(): array
    {
        $tenantId = $this->tenant->id;
        $postedEntries = JournalEntry::forTenant($tenantId)
            ->posted()
            ->whereDate('entry_date', '<=', $this->endDate)
            ->with('items.chartAccount')
            ->get();

        $accounts = ChartAccount::forTenant($tenantId)
            ->whereIn('type', ['activo', 'pasivo', 'capital'])
            ->with('children')
            ->get();

        $balances = $this->calculateAccountBalances($postedEntries);

        return [
            'date' => $this->endDate->format('Y-m-d'),
            'assets' => $this->buildAccountGroup($accounts, $balances, 'activo'),
            'liabilities' => $this->buildAccountGroup($accounts, $balances, 'pasivo'),
            'equity' => $this->buildAccountGroup($accounts, $balances, 'capital'),
            'total_assets' => $this->calculateTotal($accounts, $balances, 'activo'),
            'total_liabilities' => $this->calculateTotal($accounts, $balances, 'pasivo'),
            'total_equity' => $this->calculateTotal($accounts, $balances, 'capital'),
        ];
    }

    public function getIncomeStatement(): array
    {
        $tenantId = $this->tenant->id;
        $postedEntries = JournalEntry::forTenant($tenantId)
            ->posted()
            ->whereBetween('entry_date', [$this->startDate, $this->endDate])
            ->with('items.chartAccount')
            ->get();

        $accounts = ChartAccount::forTenant($tenantId)
            ->whereIn('type', ['ingreso', 'egreso'])
            ->get();

        $balances = $this->calculateAccountBalances($postedEntries);

        $revenues = $this->buildAccountGroup($accounts, $balances, 'ingreso');
        $expenses = $this->buildAccountGroup($accounts, $balances, 'egreso');

        $totalRevenues = $this->calculateTotal($accounts, $balances, 'ingreso');
        $totalExpenses = $this->calculateTotal($accounts, $balances, 'egreso');
        $netIncome = $totalRevenues - $totalExpenses;

        return [
            'start_date' => $this->startDate->format('Y-m-d'),
            'end_date' => $this->endDate->format('Y-m-d'),
            'revenues' => $revenues,
            'expenses' => $expenses,
            'total_revenues' => $totalRevenues,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
        ];
    }

    public function getCashFlowStatement(): array
    {
        $tenantId = $this->tenant->id;
        
        // Operativas
        $operatingEntries = JournalEntry::forTenant($tenantId)
            ->posted()
            ->whereBetween('entry_date', [$this->startDate, $this->endDate])
            ->where('type', 'diario')
            ->with('items.chartAccount')
            ->get();

        $operatingCash = $this->calculateCashFlow($operatingEntries, ['operating']);

        // Inversión (simplificado)
        $investmentCash = $this->calculateCashFlow($operatingEntries, ['investment']);

        // Financiamiento (simplificado)
        $financingCash = $this->calculateCashFlow($operatingEntries, ['financing']);

        $netCashFlow = $operatingCash + $investmentCash + $financingCash;

        return [
            'start_date' => $this->startDate->format('Y-m-d'),
            'end_date' => $this->endDate->format('Y-m-d'),
            'operating' => $operatingCash,
            'investment' => $investmentCash,
            'financing' => $financingCash,
            'net_cash_flow' => $netCashFlow,
        ];
    }

    protected function calculateAccountBalances($entries): array
    {
        $balances = [];

        foreach ($entries as $entry) {
            foreach ($entry->items as $item) {
                $accountId = $item->chart_account_id;
                
                if (!isset($balances[$accountId])) {
                    $balances[$accountId] = 0;
                }

                if ($item->type === 'debit') {
                    $balances[$accountId] += $item->amount;
                } else {
                    $balances[$accountId] -= $item->amount;
                }
            }
        }

        return $balances;
    }

    protected function buildAccountGroup($accounts, $balances, $type): array
    {
        $groupAccounts = $accounts->where('type', $type);
        $result = ['accounts' => [], 'total' => 0];

        foreach ($groupAccounts->whereNull('parent_id') as $account) {
            $balance = $this->calculateAccountBalanceWithChildren($account, $balances);
            $result['accounts'][] = [
                'id' => $account->id,
                'code' => $account->code,
                'name' => $account->name,
                'balance' => $balance,
            ];
            $result['total'] += $balance;
        }

        return $result;
    }

    protected function calculateAccountBalanceWithChildren($account, $balances): float
    {
        $balance = $balances[$account->id] ?? 0;

        foreach ($account->children as $child) {
            $balance += $this->calculateAccountBalanceWithChildren($child, $balances);
        }

        return $balance;
    }

    protected function calculateTotal($accounts, $balances, $type): float
    {
        $total = 0;
        $typeAccounts = $accounts->where('type', $type);

        foreach ($typeAccounts as $account) {
            $total += $this->calculateAccountBalanceWithChildren($account, $balances);
        }

        return $total;
    }

    protected function calculateCashFlow($entries, $categories): float
    {
        // Implementación simplificada
        // En producción, esto debería considerar cuentas específicas de flujo de efectivo
        return 0;
    }
}
