<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $table = 'receipt';
    protected $fillable = [
        'year_id',    
        'month',
        'department_id',
        'bulk_entry_master_id',
        'receipt_no',
        'cheque_no',
        'exact_amount'
    ];
}
