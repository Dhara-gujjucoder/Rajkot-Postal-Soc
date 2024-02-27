<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'is_active',
        'status',
    ];

    // protected static function booted()
    // {
    //     static::addGlobalScope('active', function (Builder $builder) {
    //         $builder->where('status', 1);
    //     });
    // }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
