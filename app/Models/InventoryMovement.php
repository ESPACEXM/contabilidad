<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'type',
        'movement_date',
        'quantity',
        'unit_cost',
        'total_cost',
        'reference',
        'notes',
        'stock_before',
        'stock_after',
        'created_by',
        'journal_entry_id',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'stock_before' => 'decimal:3',
        'stock_after' => 'decimal:3',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeEntries($query)
    {
        return $query->whereIn('type', ['entry', 'purchase', 'return']);
    }

    public function scopeExits($query)
    {
        return $query->whereIn('type', ['exit', 'sale', 'transfer']);
    }
}
