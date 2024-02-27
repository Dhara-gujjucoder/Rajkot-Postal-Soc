<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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




    public function share_amount()
    {
        return $this->hasOne(MemberShare::class, 'member_id', 'member_share_id')->withTrashed();
    }

}
