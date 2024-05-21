<?php

namespace App\Models;

use App\Models\LoanEMI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        'emi_amount','date', 'loan_settlement_amt', 'loan_settlment_month', 'is_old_loan_settled' , 'total_share_amt', 'stamp_duty', 'fixed_saving', 'total_amt', 'payment_type',    'bank_name',    'cheque_no',    'g1_member_id',    'g2_member_id',    'cheque_no', 'bank_name',
        'status','remaining_fixed_saving','remaining_share_amount','remaining_loan_amount','gcheque_no','gbank_name'
    ];
    // 1 for active, 2 for complete 3 for settle or close
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeRunningLoan($query)
    {
        return $query->whereHas('loan_emis', function ($q)  {
            $q->pending();
        });
    }

    protected function Status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value == 1 ? __('Active') : ($value ==  2  ? __('Completed') :  __('Settled')),
        );
    }

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

    public function loan_emis()
    {
        return $this->hasMany(LoanEMI::class, 'loan_master_id', 'id');
    }

//    public function get_status(){
//         // $array = [1 => __('Active'),2  => __('Completed'), 3 => __('Settled')];

//    }

}
