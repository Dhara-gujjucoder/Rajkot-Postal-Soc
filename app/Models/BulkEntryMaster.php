<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulkEntryMaster extends Model
{
    use HasFactory;
    protected $table = 'bulk_entry_master';
    protected $fillable = [
        'year_id',
        'bulk_master_id',
        'department_id',
        'department_name',
        'rec_no',
        'receipt_id',
        'month',
        'month_total',
        'is_ms_applicable',
        'ms_value',
        'department_total',
        'status',
        'exact_amount',
        'cheque_no',
        'created_by'
    ];
    protected function Status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value == 2 ? __('Done') : __('Draft'),
        );
    }
    protected static function booted()
    {
        static::addGlobalScope('current_year', function (Builder $builder) {
            $builder->where('year_id', currentYear()->id);
        });
    }
    public function receipt()
    {
        return $this->hasOne(Receipt::class, 'id', 'receipt_id');
    }
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function bulk_entries()
    {
        return $this->hasMany(BulkEntry::class, 'bulk_entry_master_id', 'id')->where('status',2);    // 1->Draft 2->complite
    }

    public function members()
    {
        return $this->hasManyThrough(Member::class,BulkEntry::class,'bulk_entry_master_id','id','id','member_id')->withTrashed();
    }
}
