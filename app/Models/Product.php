<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'sku',
        'name',
        'description',
        'type',
        'unit_price',
        'cost_price',
        'unit_of_measure',
        'stock_quantity',
        'min_stock',
        'max_stock',
        'valuation_method',
        'image',
        'track_inventory',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'decimal:3',
        'min_stock' => 'decimal:3',
        'max_stock' => 'decimal:3',
        'track_inventory' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class)->orderBy('movement_date', 'desc');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock');
    }

    public function hasLowStock(): bool
    {
        return $this->track_inventory && $this->stock_quantity <= $this->min_stock;
    }

    public function updateStock($quantity, $type, $unitCost = null)
    {
        if (!$this->track_inventory) {
            return;
        }

        $stockBefore = $this->stock_quantity;

        if ($type === 'entry' || $type === 'purchase' || $type === 'return') {
            $this->increment('stock_quantity', $quantity);
            
            // Actualizar costo según método de valuación
            if ($unitCost && in_array($this->valuation_method, ['average', 'fifo', 'lifo'])) {
                $this->updateCostPrice($unitCost, $quantity, $type);
            }
        } else {
            $this->decrement('stock_quantity', $quantity);
        }

        $this->save();

        return [
            'stock_before' => $stockBefore,
            'stock_after' => $this->stock_quantity,
        ];
    }

    protected function updateCostPrice($newCost, $quantity, $type)
    {
        if ($this->valuation_method === 'average') {
            $totalCost = ($this->stock_quantity * $this->cost_price) + ($quantity * $newCost);
            $totalQuantity = $this->stock_quantity + $quantity;
            $this->cost_price = $totalQuantity > 0 ? $totalCost / $totalQuantity : $newCost;
        }
    }
}
