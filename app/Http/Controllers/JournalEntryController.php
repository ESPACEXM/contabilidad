<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\ChartAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalEntryController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::forTenant(Auth::user()->tenant_id)
            ->with(['items.chartAccount', 'creator', 'approver'])
            ->orderBy('entry_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('journal-entries.index', compact('entries'));
    }

    public function create()
    {
        $accounts = ChartAccount::forTenant(Auth::user()->tenant_id)
            ->where('allows_movements', true)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return view('journal-entries.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'type' => 'required|in:diario,ingresos,egresos,apertura,cierre',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:2',
            'items.*.chart_account_id' => 'required|exists:chart_accounts,id',
            'items.*.type' => 'required|in:debit,credit',
            'items.*.amount' => 'required|numeric|min:0.01',
            'items.*.description' => 'nullable|string',
        ]);

        $tenantId = Auth::user()->tenant_id;

        $entry = JournalEntry::create([
            'tenant_id' => $tenantId,
            'entry_number' => JournalEntry::generateEntryNumber($tenantId),
            'entry_date' => $validated['entry_date'],
            'type' => $validated['type'],
            'reference' => $validated['reference'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($validated['items'] as $index => $item) {
            $entryItem = JournalEntryItem::create([
                'journal_entry_id' => $entry->id,
                'chart_account_id' => $item['chart_account_id'],
                'type' => $item['type'],
                'amount' => $item['amount'],
                'description' => $item['description'] ?? null,
                'line_number' => $index + 1,
            ]);

            if ($item['type'] === 'debit') {
                $totalDebit += $item['amount'];
            } else {
                $totalCredit += $item['amount'];
            }
        }

        $entry->update([
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ]);

        if ($entry->isBalanced() && $request->has('post_now')) {
            $entry->post();
            return redirect()->route('journal-entries.index')
                ->with('success', 'Póliza creada y contabilizada exitosamente.');
        }

        return redirect()->route('journal-entries.index')
            ->with('success', 'Póliza creada exitosamente.');
    }

    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load(['items.chartAccount', 'creator', 'approver']);
        return view('journal-entries.show', compact('journalEntry'));
    }

    public function edit(JournalEntry $journalEntry)
    {
        if ($journalEntry->status === 'posted') {
            return redirect()->route('journal-entries.index')
                ->with('error', 'No se puede editar una póliza contabilizada.');
        }

        $accounts = ChartAccount::forTenant(Auth::user()->tenant_id)
            ->where('allows_movements', true)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $journalEntry->load('items');

        return view('journal-entries.edit', compact('journalEntry', 'accounts'));
    }

    public function update(Request $request, JournalEntry $journalEntry)
    {
        if ($journalEntry->status === 'posted') {
            return redirect()->route('journal-entries.index')
                ->with('error', 'No se puede editar una póliza contabilizada.');
        }

        $validated = $request->validate([
            'entry_date' => 'required|date',
            'type' => 'required|in:diario,ingresos,egresos,apertura,cierre',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'items' => 'required|array|min:2',
            'items.*.chart_account_id' => 'required|exists:chart_accounts,id',
            'items.*.type' => 'required|in:debit,credit',
            'items.*.amount' => 'required|numeric|min:0.01',
            'items.*.description' => 'nullable|string',
        ]);

        $journalEntry->update([
            'entry_date' => $validated['entry_date'],
            'type' => $validated['type'],
            'reference' => $validated['reference'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // Eliminar items existentes
        $journalEntry->items()->delete();

        // Crear nuevos items
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($validated['items'] as $index => $item) {
            $entryItem = JournalEntryItem::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_account_id' => $item['chart_account_id'],
                'type' => $item['type'],
                'amount' => $item['amount'],
                'description' => $item['description'] ?? null,
                'line_number' => $index + 1,
            ]);

            if ($item['type'] === 'debit') {
                $totalDebit += $item['amount'];
            } else {
                $totalCredit += $item['amount'];
            }
        }

        $journalEntry->update([
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
        ]);

        return redirect()->route('journal-entries.index')
            ->with('success', 'Póliza actualizada exitosamente.');
    }

    public function destroy(JournalEntry $journalEntry)
    {
        if ($journalEntry->status === 'posted') {
            return redirect()->route('journal-entries.index')
                ->with('error', 'No se puede eliminar una póliza contabilizada.');
        }

        $journalEntry->delete();

        return redirect()->route('journal-entries.index')
            ->with('success', 'Póliza eliminada exitosamente.');
    }

    public function post(JournalEntry $journalEntry)
    {
        try {
            $journalEntry->post();
            return redirect()->route('journal-entries.index')
                ->with('success', 'Póliza contabilizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('journal-entries.index')
                ->with('error', $e->getMessage());
        }
    }
}
