<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'parent_id',
        'code',
        'name',
        'description',
        'type',
        'nature',
        'level',
        'allows_movements',
        'is_active',
        'initial_balance',
        'order',
    ];

    protected $casts = [
        'allows_movements' => 'boolean',
        'is_active' => 'boolean',
        'initial_balance' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function parent()
    {
        return $this->belongsTo(ChartAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ChartAccount::class, 'parent_id')->orderBy('code');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeRootAccounts($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullCodeAttribute()
    {
        return $this->code . ' - ' . $this->name;
    }

    public function hasChildren()
    {
        return $this->children()->exists();
    }

    public function journalEntryItems()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function budgetItems()
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function getBalance($asOfDate = null)
    {
        $balance = $this->initial_balance ?? 0;
        
        $items = $this->journalEntryItems()
            ->whereHas('journalEntry', function($query) use ($asOfDate) {
                $query->where('status', 'posted');
                if ($asOfDate) {
                    $query->where('entry_date', '<=', $asOfDate);
                }
            })
            ->get();

        foreach ($items as $item) {
            if ($item->isDebit()) {
                $balance += $item->amount;
            } else {
                $balance -= $item->amount;
            }
        }

        // Ajustar según naturaleza de la cuenta
        if ($this->nature === 'deudora') {
            return $balance;
        } else {
            return -$balance;
        }
    }
}

