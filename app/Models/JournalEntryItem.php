<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_entry_id',
        'chart_account_id',
        'type',
        'amount',
        'description',
        'reference',
        'line_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }

    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }
}
