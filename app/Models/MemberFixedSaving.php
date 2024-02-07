<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberFixedSaving extends Model
{
    use HasFactory;
    protected $table = 'member_fixed_saving';
    protected $fillable = [
        'ledger_account_id',
        'member_id',
        'month',
        'fixed_amount',
        'year_id',
        'status'
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id')->withTrashed();
    }



}
