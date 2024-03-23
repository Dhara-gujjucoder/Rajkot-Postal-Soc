<?php

namespace App\Models;

use App\Models\LedgerAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetaDoubleEntry extends Model
{
    // use HasFactory, SoftDeletes;
    protected $table = 'meta_double_entry';
    protected $fillable = [
        'mde_id',
        'ledger_ac_id',
        'share',
        'particular',
        'amount',
        'member_id',
        'month',
        'type',
    ];

    public function LedgerAcountName()
    {
        return $this->belongsTo(LedgerAccount::class, 'ledger_ac_id', 'id');
    }
}
