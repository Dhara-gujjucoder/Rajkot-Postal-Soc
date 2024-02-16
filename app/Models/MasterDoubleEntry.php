<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterDoubleEntry extends Model
{
    // use HasFactory, SoftDeletes;
    protected $table = 'master_double_entry';
    protected $fillable = [
        'entry_id',
        'credit_amount',
        'debit_amount',
        'date',
        'description',
        'year_id',
    ];

    public function meta_entry()
    {
        return $this->hasMany(MetaDoubleEntry::class, 'mde_id', 'id');
    }


}
