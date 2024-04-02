<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceSheet extends Model
{
    use HasFactory;
    protected $table = 'balance_sheet';
    protected $guarded = [];
    // protected $fillable = [
    //     'ledger_ac_id',
    //     'provision_fb',
    //     'provision_member_welfare',
    //     'provision_reserve_fund',
    //     'provision_reserve_bonus',
    //     'provision_reserve_charity',
    //     'provision_dividend_equity_fund',
    //     'provision_federation_fund',
    //     'year_id',

    // ];

}
