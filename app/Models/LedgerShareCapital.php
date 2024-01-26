<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerShareCapital extends Model
{
    use HasFactory;
    protected $table = 'ledger_sharecapital';
    protected $fillable = [
        'ledger_account_id',
        'member_id',
        'share_code',
        'share_amount',
        'year_id',
        'status',
        'created_date',
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id')->withTrashed();
    }



}
