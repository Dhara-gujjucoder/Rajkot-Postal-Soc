<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkEntry extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',	
        'year_id',	
        'ledger_group_id',
        'month',	
        'rec_no',	
        'principal',	
        'interest',	
        'fixed',	
        'total_amount'
    ];

}
