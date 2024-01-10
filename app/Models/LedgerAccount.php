<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LedgerAccount extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'ledger_group_id',
        'account_name',
        'user_id',
        'year_id',
        'opening_balance',
        'type',
        'created_by',
        'status',

    ];


    protected function yearId(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => FinancialYear::where('is_current',1)->pluck('id')->first(),
        );
    }

    public function LedgerGroupId()
    {
        return $this->belongsTo(LedgerGroup::class, 'ledger_group_id', 'id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
