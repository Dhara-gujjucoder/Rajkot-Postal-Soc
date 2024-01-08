<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;
    protected $table = 'financial_year';
    protected $fillable = [
        'title',
        'start_year',
        'start_month',
        'end_year',
        'end_month',
        'is_current',
        'status',
    ];

}
