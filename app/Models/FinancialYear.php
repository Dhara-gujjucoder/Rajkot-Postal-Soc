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

        'total_saving',
        'total_interest',
        'total_share',
        'total_share_amount',
        'balance',

        // Total Saving
        // Total Interest
        // Total Share
        // Balance = Total Saving + Total Interest + Total Share
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
