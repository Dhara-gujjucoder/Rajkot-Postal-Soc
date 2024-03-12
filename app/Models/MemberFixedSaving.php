<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberFixedSaving extends Model
{
    use HasFactory;
    protected $table = 'member_fixed_saving';
    protected $fillable = [
        'ledger_account_id',
        'member_id',
        'month',
        'fixed_amount',
        'year_id',
        'status'
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id')->withTrashed();
    }

    protected static function booted(): void
    {
        static::addGlobalScope('year', function (Builder $builder) {
            $builder->where('year_id',currentYear()->id);
        });
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status',1);
        });
    }

}

 
