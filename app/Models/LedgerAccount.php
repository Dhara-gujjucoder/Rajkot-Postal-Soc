<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LedgerAccount extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=[];

    public function LedgerGroupId()
    {
        return $this->belongsTo(LedgerGroup::class, 'ledger_group_id', 'id');
    }
}
