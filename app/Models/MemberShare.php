<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberShare extends Model
{
    use HasFactory;
    protected $table = 'member_share';
    protected $fillable = [
        'ledger_account_id',
        'member_id',
        'share_code',
        'share_amount',
        'year_id',
        'status',
        'purchase_on',
        'sold_on'
    ];
    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id')->withTrashed();
    }

    public function share_detail()
    {
        return $this->hasMany(MemberShareDetail::class, 'member_share_id', 'id');
    }
}
