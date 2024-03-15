<?php

namespace App\Models;

use App\Models\LedgerGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulkMaster extends Model
{
    use HasFactory;
    protected $table = 'bulk_master';
    protected $guarded = [];

    protected function Status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value == 2 ? __('Completed') : __('Draft'),
        );
    }

    protected function LedgerAcId(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => LedgerAccount::where('ledger_group_id',10)->first()->id,
        );
    }

    protected static function booted()
    {
        static::addGlobalScope('current_year', function (Builder $builder) {
            $builder->where('year_id', currentYear()->id);
        });
    }

    public function rdc_ledger_account()
    {
        return $this->hasOne(LedgerAccount::class, 'id', 'ledger_ac_id');
    }


}

