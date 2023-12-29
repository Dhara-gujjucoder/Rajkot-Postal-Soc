<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanInterest extends Model
{
    use HasFactory;
    protected $table = "loan_interest";
    protected $guarded = [];
}
