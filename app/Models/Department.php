<?php

namespace App\Models;

use App\Models\LedgerGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'department';
    protected $guarded=[];

    public function LedgerGroupId()
    {
        return $this->belongsTo(LedgerGroup::class, 'ledger_group_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'department_id', 'id');
    }
}
