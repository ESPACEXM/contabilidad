<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::forTenant(Auth::user()->tenant_id)
            ->with('parent')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('product-categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = ProductCategory::forTenant(Auth::user()->tenant_id)
            ->active()
            ->get();

        return view('product-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'is_active' => 'boolean',
        ]);

        $category = ProductCategory::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('product-categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function show(ProductCategory $productCategory)
    {
        $productCategory->load(['parent', 'children', 'products']);
        return view('product-categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        $categories = ProductCategory::forTenant(Auth::user()->tenant_id)
            ->where('id', '!=', $productCategory->id)
            ->active()
            ->get();

        return view('product-categories.edit', compact('productCategory', 'categories'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id|different:id',
            'is_active' => 'boolean',
        ]);

        $productCategory->update([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('product-categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->count() > 0) {
            return redirect()->route('product-categories.index')
                ->with('error', 'No se puede eliminar una categoría que tiene productos asociados.');
        }

        $productCategory->delete();
        return redirect()->route('product-categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
