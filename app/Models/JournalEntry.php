<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'entry_number',
        'entry_date',
        'type',
        'reference',
        'description',
        'total_debit',
        'total_credit',
        'status',
        'created_by',
        'approved_by',
        'posted_at',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'posted_at' => 'datetime',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(JournalEntryItem::class)->orderBy('line_number');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function isBalanced(): bool
    {
        return abs($this->total_debit - $this->total_credit) < 0.01;
    }

    public function post()
    {
        if (!$this->isBalanced()) {
            throw new \Exception('La póliza no está balanceada. Debe = Haber');
        }

        $this->update([
            'status' => 'posted',
            'posted_at' => now(),
            'approved_by' => auth()->id(),
        ]);
    }

    public static function generateEntryNumber($tenantId): string
    {
        $year = date('Y');
        $count = static::where('tenant_id', $tenantId)
            ->whereYear('created_at', $year)
            ->count() + 1;
        
        return 'POL-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
