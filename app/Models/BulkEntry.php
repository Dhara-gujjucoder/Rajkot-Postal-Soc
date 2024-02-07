<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkEntry extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'bulk_entry_master_id',
        'user_id',	
        'member_id',
        'department_id',
        'year_id',	
        'ledger_group_id',
        'month',	
        'rec_no',	
        'principal',	
        'interest',	
        'fixed',
        'ms',
        'total_amount',
        'status'
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function master_entry()
    {
        return $this->hasOne(BulkEntryMaster::class, 'id', 'bulk_entry_master_id');
    }

    public function department()
    {
        return $this->hasOne(Member::class, 'user_id', 'user_id');
    }
}
