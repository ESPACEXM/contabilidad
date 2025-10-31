<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryMovement::forTenant(Auth::user()->tenant_id)
            ->with(['product', 'creator']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->where('movement_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('movement_date', '<=', $request->date_to);
        }

        $movements = $query->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $products = Product::forTenant(Auth::user()->tenant_id)
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();

        return view('inventory.index', compact('movements', 'products'));
    }

    public function create()
    {
        $products = Product::forTenant(Auth::user()->tenant_id)
            ->where('track_inventory', true)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entry,exit,adjustment,transfer',
            'movement_date' => 'required|date',
            'quantity' => 'required|numeric|min:0.001',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->tenant_id !== Auth::user()->tenant_id) {
            return back()->withErrors(['product_id' => 'Producto no válido.']);
        }

        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['unit_cost'] = $validated['unit_cost'] ?? $product->cost_price;
        $validated['total_cost'] = $validated['quantity'] * $validated['unit_cost'];
        $validated['created_by'] = Auth::id();

        // Actualizar stock del producto
        $stockUpdate = $product->updateStock(
            $validated['quantity'],
            $validated['type'],
            $validated['unit_cost']
        );

        $validated['stock_before'] = $stockUpdate['stock_before'];
        $validated['stock_after'] = $stockUpdate['stock_after'];

        $movement = InventoryMovement::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Movimiento de inventario registrado exitosamente.');
    }

    public function kardex(Product $product)
    {
        $movements = InventoryMovement::forTenant(Auth::user()->tenant_id)
            ->where('product_id', $product->id)
            ->orderBy('movement_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->with('creator')
            ->get();

        $runningBalance = 0;
        $runningCost = 0;

        $kardex = $movements->map(function($movement) use (&$runningBalance, &$runningCost) {
            if (in_array($movement->type, ['entry', 'purchase', 'return'])) {
                $runningBalance += $movement->quantity;
                $runningCost = ($runningCost * ($runningBalance - $movement->quantity) + ($movement->unit_cost * $movement->quantity)) / max($runningBalance, 1);
            } else {
                $runningBalance -= $movement->quantity;
            }

            return [
                'movement' => $movement,
                'running_balance' => $runningBalance,
                'running_cost' => $runningCost,
            ];
        });

        return view('inventory.kardex', compact('product', 'kardex'));
    }
}
