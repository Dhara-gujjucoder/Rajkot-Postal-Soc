<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;
    use HasFactory;
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


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function department()
    {
        return $this->hasOne(Department::class,  'id', 'department_id');
    }

    public function getFullnameAttribute()
    {
        return "{$this->user->name} ( $this->registration_no )";
    }

    public function getNameAttribute()
    {
        return "{$this->user->name}";
    }

    public function fixed_saving_ledger_account()
    {
        return $this->hasOne(LedgerAccount::class, 'member_id', 'id')->where('ledger_group_id', 1);
    }

    public function share_ledger_account()
    {
        return $this->hasOne(LedgerAccount::class, 'member_id', 'id')->where('ledger_group_id', 2);
    }

    public function loan_ledger_account()
    {
        return $this->hasOne(LedgerAccount::class, 'member_id', 'id')->where('ledger_group_id', 3);
    }

    public function shares()
    {
        return $this->hasMany(MemberShare::class, 'member_id', 'id')->where('status', 1);
    }

    public function fixed_saving()
    {
        return $this->hasMany(MemberFixedSaving::class, 'member_id', 'id')->where('status', 1);
    }
    public function loan()
    {
        return $this->hasOne(LoanMaster::class, 'member_id', 'id')->where('status', 1);
    }

    public function getMemberFixedAttribute()
    {
        $fixed_saving['member_fixed_saving'] = BulkEntry::where('member_id', $this->id)->where('status', 2)->sum('fixed');
        $fixed_saving['total_fixed_saving'] = count(getMonthsOfYear(currentYear()->id)) * current_fixed_saving()->monthly_saving;
        $fixed_saving['remaining_fixed_saving'] = $fixed_saving['total_fixed_saving'] - $fixed_saving['member_fixed_saving'] > 0 ? $fixed_saving['total_fixed_saving'] - $fixed_saving['member_fixed_saving'] : 0;
        return $fixed_saving;
    }
}
