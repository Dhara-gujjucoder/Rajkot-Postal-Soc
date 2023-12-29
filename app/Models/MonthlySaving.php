<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySaving extends Model
{
    use HasFactory;
    protected $table = "monthly_saving";
    protected $guarded = [];
}
