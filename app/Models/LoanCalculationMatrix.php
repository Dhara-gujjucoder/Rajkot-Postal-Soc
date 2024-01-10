<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanCalculationMatrix extends Model
{
    protected $table = 'loan_calculation_matrix';
    protected $fillable = [
        'amount',
        'minimum_emi',
        'status',
    ];

}
