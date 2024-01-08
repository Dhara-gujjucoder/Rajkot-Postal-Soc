<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDeduction extends Model
{
    use HasFactory;
    protected $table = "salary_deduction";
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function ledger_account()
    {
        return $this->hasOne(LedgerAccount::class,'id','ledger_ac_id');
    }
    
}
