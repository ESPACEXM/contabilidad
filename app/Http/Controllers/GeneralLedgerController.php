<?php

namespace App\Http\Controllers;

use App\Models\ChartAccount;
use App\Models\JournalEntryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        $tenant = Auth::user()->tenant;
        
        $accountId = $request->input('account_id');
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $accounts = ChartAccount::forTenant($tenant->id)
            ->where('allows_movements', true)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $ledger = [];
        $initialBalance = 0;
        $account = null;

        if ($accountId) {
            $account = ChartAccount::forTenant($tenant->id)->findOrFail($accountId);
            
            // Obtener saldo inicial (antes de la fecha de inicio)
            $initialBalance = $this->calculateInitialBalance($account, $startDate);
            
            // Obtener movimientos del período
            $items = JournalEntryItem::whereHas('journalEntry', function($query) use ($tenant, $startDate, $endDate) {
                $query->where('tenant_id', $tenant->id)
                    ->where('status', 'posted')
                    ->whereBetween('entry_date', [$startDate, $endDate]);
            })
            ->where('chart_account_id', $accountId)
            ->with(['journalEntry' => function($query) {
                $query->orderBy('entry_date', 'asc')->orderBy('id', 'asc');
            }, 'chartAccount'])
            ->get()
            ->sortBy(function($item) {
                return $item->journalEntry->entry_date->format('Y-m-d') . '-' . str_pad($item->journalEntry->id, 10, '0', STR_PAD_LEFT) . '-' . str_pad($item->line_number, 5, '0', STR_PAD_LEFT);
            })
            ->values();

            $runningBalance = $initialBalance;
            $ledger = $items->map(function($item) use (&$runningBalance, $account) {
                $debit = $item->type === 'debit' ? $item->amount : 0;
                $credit = $item->type === 'credit' ? $item->amount : 0;
                
                // Calcular saldo según naturaleza de la cuenta
                if ($account->nature === 'deudora') {
                    $runningBalance = $runningBalance + $debit - $credit;
                } else {
                    $runningBalance = $runningBalance + $credit - $debit;
                }

                return [
                    'date' => $item->journalEntry->entry_date,
                    'entry_number' => $item->journalEntry->entry_number,
                    'reference' => $item->reference ?? $item->journalEntry->reference,
                    'description' => $item->description ?? $item->journalEntry->description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'balance' => $runningBalance,
                ];
            });
        }

        return view('general-ledger.index', compact(
            'accounts', 
            'account', 
            'ledger', 
            'initialBalance',
            'startDate', 
            'endDate'
        ));
    }

    protected function calculateInitialBalance(ChartAccount $account, $startDate): float
    {
        $tenant = Auth::user()->tenant;
        
        // Saldo inicial de la cuenta
        $initialBalance = $account->initial_balance ?? 0;
        
        // Movimientos antes de la fecha de inicio
        $items = JournalEntryItem::whereHas('journalEntry', function($query) use ($tenant, $startDate) {
            $query->where('tenant_id', $tenant->id)
                ->where('status', 'posted')
                ->whereDate('entry_date', '<', $startDate);
        })
        ->where('chart_account_id', $account->id)
        ->get();

        foreach ($items as $item) {
            if ($account->nature === 'deudora') {
                if ($item->type === 'debit') {
                    $initialBalance += $item->amount;
                } else {
                    $initialBalance -= $item->amount;
                }
            } else {
                if ($item->type === 'credit') {
                    $initialBalance += $item->amount;
                } else {
                    $initialBalance -= $item->amount;
                }
            }
        }

        return $initialBalance;
    }
}

