<?php

namespace App\Models;

use App\Models\LoanEMI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanMaster extends Model
{
    use HasFactory;
    protected $table = 'loan_master';
    protected $fillable = [
        'ledger_account_id',
        'loan_no',
        'year_id',
        'month',
        'member_id',
        'start_month',
        'end_month',
        'loan_id',
        'principal_amt',
        'emi_amount',    'loan_settlement_amt',    'total_share_amt',    'stamp_duty',    'fixed_saving',    'total_amt', 'payment_type',    'bank_name',    'cheque_no',    'g1_member_id',    'g2_member_id',    'gcheque_no', 'gbank_name'
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function guarantor1()
    {
        return $this->hasOne(Member::class, 'id', 'g1_member_id');
    }

    public function guarantor2()
    {
        return $this->hasOne(Member::class, 'id', 'g2_member_id');
    }

    protected function Status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value == 1 ? __('Active') : ($value ==  2  ? __('Settled') :  __('Completed')),
        );
    }
    public function loan_emis_d()
    {
        return $this->hasMany(LoanEMI::class, 'loan_master_id', 'id');
    }


}
