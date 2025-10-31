<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FinancialStatementService;
use App\Services\FinancialRatioService;
use Carbon\Carbon;

class FinancialStatementController extends Controller
{
    public function balanceSheet(Request $request)
    {
        $tenant = Auth::user()->tenant;
        
        $endDate = $request->input('date', Carbon::now()->format('Y-m-d'));
        $startDate = Carbon::parse($endDate)->startOfYear();

        $statementService = new FinancialStatementService($tenant);
        $statementService->setPeriod($startDate, Carbon::parse($endDate));

        $balanceSheet = $statementService->getBalanceSheet();

        return view('financial-statements.balance-sheet', compact('balanceSheet', 'endDate'));
    }

    public function incomeStatement(Request $request)
    {
        $tenant = Auth::user()->tenant;
        
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $statementService = new FinancialStatementService($tenant);
        $statementService->setPeriod(Carbon::parse($startDate), Carbon::parse($endDate));

        $incomeStatement = $statementService->getIncomeStatement();

        return view('financial-statements.income-statement', compact('incomeStatement', 'startDate', 'endDate'));
    }

    public function cashFlowStatement(Request $request)
    {
        $tenant = Auth::user()->tenant;
        
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $statementService = new FinancialStatementService($tenant);
        $statementService->setPeriod(Carbon::parse($startDate), Carbon::parse($endDate));

        $cashFlow = $statementService->getCashFlowStatement();

        return view('financial-statements.cash-flow', compact('cashFlow', 'startDate', 'endDate'));
    }

    public function analysis(Request $request)
    {
        $tenant = Auth::user()->tenant;
        
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $statementService = new FinancialStatementService($tenant);
        $statementService->setPeriod(Carbon::parse($startDate), Carbon::parse($endDate));

        $ratioService = new FinancialRatioService($statementService);
        $ratios = $ratioService->getAllRatios();

        // Análisis vertical
        $balanceSheet = $statementService->getBalanceSheet();
        $incomeStatement = $statementService->getIncomeStatement();

        $verticalAnalysis = $this->calculateVerticalAnalysis($balanceSheet, $incomeStatement);

        // Análisis horizontal
        $previousStartDate = Carbon::parse($startDate)->subYear();
        $previousEndDate = Carbon::parse($endDate)->subYear();

        $previousStatementService = new FinancialStatementService($tenant);
        $previousStatementService->setPeriod($previousStartDate, $previousEndDate);

        $previousBalanceSheet = $previousStatementService->getBalanceSheet();
        $previousIncomeStatement = $previousStatementService->getIncomeStatement();

        $horizontalAnalysis = $this->calculateHorizontalAnalysis(
            $balanceSheet,
            $previousBalanceSheet,
            $incomeStatement,
            $previousIncomeStatement
        );

        return view('financial-statements.analysis', compact(
            'ratios',
            'verticalAnalysis',
            'horizontalAnalysis',
            'startDate',
            'endDate'
        ));
    }

    protected function calculateVerticalAnalysis($balanceSheet, $incomeStatement): array
    {
        $analysis = [
            'balance_sheet' => [],
            'income_statement' => [],
        ];

        $totalAssets = $balanceSheet['total_assets'] ?? 1;
        
        foreach ($balanceSheet['assets']['accounts'] ?? [] as $account) {
            $analysis['balance_sheet'][] = [
                'account' => $account['name'],
                'amount' => $account['balance'],
                'percentage' => ($account['balance'] / $totalAssets) * 100,
            ];
        }

        $totalRevenues = $incomeStatement['revenues']['total'] ?? 1;

        foreach ($incomeStatement['revenues']['accounts'] ?? [] as $account) {
            $analysis['income_statement'][] = [
                'account' => $account['name'],
                'amount' => $account['balance'],
                'percentage' => ($account['balance'] / $totalRevenues) * 100,
            ];
        }

        return $analysis;
    }

    protected function calculateHorizontalAnalysis($currentBS, $previousBS, $currentIS, $previousIS): array
    {
        $analysis = [];

        $currentTotalAssets = $currentBS['total_assets'] ?? 0;
        $previousTotalAssets = $previousBS['total_assets'] ?? 0;
        
        $variation = $currentTotalAssets - $previousTotalAssets;
        $variationPercentage = $previousTotalAssets != 0 
            ? ($variation / $previousTotalAssets) * 100 
            : 0;

        $analysis['assets'] = [
            'current' => $currentTotalAssets,
            'previous' => $previousTotalAssets,
            'variation' => $variation,
            'variation_percentage' => $variationPercentage,
        ];

        $currentRevenues = $currentIS['revenues']['total'] ?? 0;
        $previousRevenues = $previousIS['revenues']['total'] ?? 0;

        $revenueVariation = $currentRevenues - $previousRevenues;
        $revenueVariationPercentage = $previousRevenues != 0 
            ? ($revenueVariation / $previousRevenues) * 100 
            : 0;

        $analysis['revenues'] = [
            'current' => $currentRevenues,
            'previous' => $previousRevenues,
            'variation' => $revenueVariation,
            'variation_percentage' => $revenueVariationPercentage,
        ];

        return $analysis;
    }
}
