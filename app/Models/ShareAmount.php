<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ShareAmount extends Model
{
    use HasFactory;
    protected $table = "share_amount";
    protected $append = ['current_share_amount'];
    protected $guarded = [];

    protected function current_share_amount(): Attribute
{
    return Attribute::make(
        get: fn (string $value) => ShareAmount::where('is_active',1)->first(),
        // set: fn (string $value) => strtolower($value),

    );
}

}
