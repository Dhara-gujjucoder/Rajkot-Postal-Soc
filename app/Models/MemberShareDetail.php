<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberShareDetail extends Model
{
    use HasFactory;
    protected $table = 'member_share_detail';
    protected $fillable = [
        'member_share_id',
        'member_id',
        'year_id',
        'is_purchase',
        'is_sold',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('year', function (Builder $builder) {
            $builder->where('year_id',currentYear()->id);
        });
    }

    public function share()
    {
        return $this->hasOne(MemberShare::class, 'id', 'member_share_id');
    }
}
