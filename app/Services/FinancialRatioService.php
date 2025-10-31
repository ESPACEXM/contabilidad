<?php

namespace App\Services;

class FinancialRatioService
{
    protected $statementService;

    public function __construct(FinancialStatementService $statementService)
    {
        $this->statementService = $statementService;
    }

    public function getAllRatios(): array
    {
        $balanceSheet = $this->statementService->getBalanceSheet();
        $incomeStatement = $this->statementService->getIncomeStatement();

        return [
            'liquidity' => $this->getLiquidityRatios($balanceSheet),
            'profitability' => $this->getProfitabilityRatios($balanceSheet, $incomeStatement),
            'leverage' => $this->getLeverageRatios($balanceSheet),
            'efficiency' => $this->getEfficiencyRatios($balanceSheet, $incomeStatement),
        ];
    }

    protected function getLiquidityRatios($balanceSheet): array
    {
        $currentAssets = $this->getAccountGroupTotal($balanceSheet['assets'], ['1.1']); // Activo Circulante
        $currentLiabilities = $this->getAccountGroupTotal($balanceSheet['liabilities'], ['2.1']); // Pasivo Circulante
        $inventory = 0; // Se calcularía de las cuentas de inventario
        $cash = 0; // Caja y bancos

        $currentRatio = $currentLiabilities > 0 ? $currentAssets / $currentLiabilities : 0;
        $quickRatio = $currentLiabilities > 0 ? ($currentAssets - $inventory) / $currentLiabilities : 0;
        $cashRatio = $currentLiabilities > 0 ? $cash / $currentLiabilities : 0;

        return [
            'current_ratio' => round($currentRatio, 2),
            'quick_ratio' => round($quickRatio, 2),
            'cash_ratio' => round($cashRatio, 2),
        ];
    }

    protected function getProfitabilityRatios($balanceSheet, $incomeStatement): array
    {
        $totalAssets = $balanceSheet['total_assets'];
        $totalEquity = $balanceSheet['total_equity'];
        $netIncome = $incomeStatement['net_income'];
        $revenues = $incomeStatement['total_revenues'];

        $roa = $totalAssets > 0 ? ($netIncome / $totalAssets) * 100 : 0;
        $roe = $totalEquity > 0 ? ($netIncome / $totalEquity) * 100 : 0;
        $netMargin = $revenues > 0 ? ($netIncome / $revenues) * 100 : 0;

        return [
            'roa' => round($roa, 2),
            'roe' => round($roe, 2),
            'net_margin' => round($netMargin, 2),
        ];
    }

    protected function getLeverageRatios($balanceSheet): array
    {
        $totalAssets = $balanceSheet['total_assets'];
        $totalLiabilities = $balanceSheet['total_liabilities'];
        $totalEquity = $balanceSheet['total_equity'];

        $debtToEquity = $totalEquity > 0 ? $totalLiabilities / $totalEquity : 0;
        $debtToAssets = $totalAssets > 0 ? ($totalLiabilities / $totalAssets) * 100 : 0;
        $equityRatio = $totalAssets > 0 ? ($totalEquity / $totalAssets) * 100 : 0;

        return [
            'debt_to_equity' => round($debtToEquity, 2),
            'debt_to_assets' => round($debtToAssets, 2),
            'equity_ratio' => round($equityRatio, 2),
        ];
    }

    protected function getEfficiencyRatios($balanceSheet, $incomeStatement): array
    {
        $totalAssets = $balanceSheet['total_assets'];
        $revenues = $incomeStatement['total_revenues'];

        $assetTurnover = $totalAssets > 0 ? $revenues / $totalAssets : 0;

        return [
            'asset_turnover' => round($assetTurnover, 2),
        ];
    }

    protected function getAccountGroupTotal($group, $codes): float
    {
        $total = 0;
        foreach ($group['accounts'] ?? [] as $account) {
            if (in_array($account['code'], $codes) || $this->codeStartsWith($account['code'], $codes)) {
                $total += $account['balance'];
            }
        }
        return $total;
    }

    protected function codeStartsWith($code, $prefixes): bool
    {
        foreach ($prefixes as $prefix) {
            if (strpos($code, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }
}
