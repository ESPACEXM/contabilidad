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
}
