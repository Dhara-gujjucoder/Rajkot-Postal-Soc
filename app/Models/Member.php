<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
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
        'birthdate',
        'signature',
        'witness_signature',
        'status',
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function department(){
        return $this->hasOne(Department::class,  'id','department_id');
    }

    public function getFullnameAttribute()
    {
        return "{$this->user->name} ( $this->registration_no )";
    }
}
