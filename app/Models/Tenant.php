<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'database',
        'email',
        'phone',
        'address',
        'rfc',
        'razon_social',
        'logo',
        'currency',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function chartAccounts()
    {
        return $this->hasMany(ChartAccount::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function financialPeriods()
    {
        return $this->hasMany(FinancialPeriod::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isActive(): bool
    {
        return $this->is_active && !$this->trashed();
    }
}

