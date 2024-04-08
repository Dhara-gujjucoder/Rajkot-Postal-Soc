<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberResign extends Model
{
    use HasFactory;
    protected $table = 'member_resign';
    protected $fillable = [
        'share_ledger_account_id',
        'fixed_ledger_account_id',
        'loan_ledger_account_id',
        'member_id',
        'principal_amount',
        'remaining_loan_amount',
        'share_amount',
        'total_fixed_saving',
        'share_amount_used',
        'fixed_saving_used',
        'total_amount',
        'payment_type',
        'cheque_no'
    ];
}

