<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanEMI extends Model
{
    use HasFactory;
    protected $table = 'loan_emis';
    protected $fillable = [
        'loan_master_id',    'month',    'member_id',  'ledger_account_id',  'principal_amt',
        'interest',    'interest_amt',    'emi',  'installment',    'rest_principal',    'status',
        'is_half_paid','payment_month','cheque_no','payment_type'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('notsettle', function (Builder $builder) {
            $builder->whereIn('status',[1,2]);
        });
    }

    protected function Status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value == 1 ? __('Pending') : ($value ==  2  ? __('Paid') :  __('Settled')),
        );
    }

    public function scopePending($query)
    {
        return $query->where('status', 1)->where('is_half_paid',0);
    }
    public function scopePaid($query)
    {
        return $query->where('status', 2)->where('is_half_paid',0);
    }
}
