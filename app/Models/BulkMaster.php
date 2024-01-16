<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

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
    protected static function booted()
    {
        static::addGlobalScope('current_year', function (Builder $builder) {
            $builder->where('year_id', currentYear()->id);
        });
    }
}

