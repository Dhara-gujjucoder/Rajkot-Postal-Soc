<?php

namespace App\Models;

use App\Models\LedgerAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoubleEntry extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'double_entries';
    protected $fillable = [
        'ledger_ac_id',
        'transaction_id',
        'particular',
        'amount',
        'date',
        'entry_type',
        'opening_balance',
        'year_id'

    ];

    public function LedgerAcountName()
    {
        return $this->belongsTo(LedgerAccount::class, 'ledger_ac_id', 'id');
    }
}
