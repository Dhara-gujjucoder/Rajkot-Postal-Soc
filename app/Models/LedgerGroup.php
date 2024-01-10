<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerGroup extends Model
{
    use HasFactory;
    protected $table = 'ledger_group';
    protected $guarded = [];

    public function ParentLedgerGroup()
    {
        return $this->belongsTo(LedgerGroup::class, 'parent_id', 'id');
    }
}
