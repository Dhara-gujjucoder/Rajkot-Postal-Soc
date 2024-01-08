<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function DepartmentName(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function getFullnameAttribute()
    {
        return "{$this->user->name} ( $this->registration_no )";
    }
}
