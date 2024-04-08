<?php

namespace App\Models;

use App\Models\MemberFixedSaving;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LedgerAccount extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'ledger_group_id',
        'account_name',
        'member_id',
        'year_id',
        'opening_balance',
        'current_balance',
        'type',
        'is_member_account',
        'created_by',
        'status',
    ];


    protected static function booted(): void
    {
        static::addGlobalScope('year', function (Builder $builder) {
            $builder->where('year_id',currentYear()->id);
        });
    }

    // protected function yearId(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn (string $value) => FinancialYear::where('is_current',1)->pluck('id')->first(),
    //     );
    // }

    public function LedgerGroupId()
    {
        return $this->belongsTo(LedgerGroup::class, 'ledger_group_id', 'id');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id')->withTrashed();
    }



}
