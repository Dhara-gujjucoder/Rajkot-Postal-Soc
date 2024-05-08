<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'uid',
        'department_id',
        'company',
        'designation',
        'gender',
        'profile_picture',
        'mobile_no',
        'whatsapp_no',
        'aadhar_card_no',
        'aadhar_card',
        'pan_no',
        'pan_card',
        'current_address',
        'parmenant_address',
        'salary',
        'da',
        'member_fee',
        'share_amt',
        'total',
        'payment_type',
        'payment_type_status',
        'cheque_no',
        'nominee_name',
        'nominee_relation',
        'registration_no',
        'department_id_proof',
        'saving_account_no',
        'bank_name',
        'ifsc_code',
        'branch_address',
        'total_share',
        'birthdate',
        'signature',
        'witness_signature',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /* Attribute for get fullname with reg no */
    public function getFullnameAttribute()
    {
        return "{$this->user->name} ( $this->registration_no )";
    }

    /* Relationship with user for get name */
    public function getNameAttribute()
    {
        return "{$this->user->name}";
    }

    protected function Status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value == 1 ? __('Active') : ($value ==  2  ? __('Resigned') :  __('Deactive')),
        );
    }

    /* Attribute for get loan remaining amount */
    public function getLoanRemainingAmountAttribute()
    {
        return loan_remaining_amount($this->id);
    }

    /* Attribute for get loan remaining amount */
    public function getRemainingFixedSavingAttribute()
    {
        return remaining_fixed_saving($this->id);
    }
    /* Relationship with user */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    /* Relationship with department */
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }


    // Remaining fixed saving

    /* Relationship of member to fixed_saving_ledger_account */
    public function fixed_saving_ledger_account()
    {
        return $this->hasOne(LedgerAccount::class, 'member_id', 'id')->where('ledger_group_id', 1);
    }

    /* Relationship of member to share_ledger_account */
    public function share_ledger_account()
    {
        return $this->hasOne(LedgerAccount::class, 'member_id', 'id')->where('ledger_group_id', 2);
    }

    /* Relationship of member to loan_ledger_account */
    public function loan_ledger_account()
    {
        return $this->hasOne(LedgerAccount::class, 'member_id', 'id')->where('ledger_group_id', 3);
    }

    /* Relationship for get member all shares */
    public function shares()
    {
        return $this->hasMany(MemberShare::class, 'member_id', 'id')->where('status', 1);
    }

    /* Attribute for get purchased_share sum of amount */
    public function getPurchasedShareAttribute()
    {
        return MemberShareDetail::where('member_id', $this->id)->where('is_purchase', 1)->withSum('share', 'share_amount')->get();
    }

    /* Attribute for get sold_share sum of amount */
    public function getSoldShareAttribute()
    {
        return MemberShareDetail::where('member_id', $this->id)->where('is_sold', 1)->withSum('share', 'share_amount')->get();
    }

    /* Relationship for get member all fixed_saving */
    public function fixed_saving()
    {
        return $this->hasMany(MemberFixedSaving::class, 'member_id', 'id')->where('status', 1)->withoutGlobalScope('bulk_entry');
    }

    /* Relationship with current loan */
    public function loan()
    {
        return $this->hasOne(LoanMaster::class, 'member_id', 'id')->where('status', 1);
    }



    /* Relationship with loan for get old loan */
    public function old_loan()
    {
        return $this->hasMany(LoanMaster::class, 'member_id', 'id')->where('status', 1);
    }

    /* get member fixed saving */
    public function getMemberFixedAttribute()
    {
        $member = Member::find($this->id);
        $fixed_saving['member_fixed_saving'] = $member->fixed_saving_ledger_account->current_balance;
        $fixed_saving['remaining_fixed_saving'] = $member->remaining_fixed_saving;
        return $fixed_saving;
    }
}
