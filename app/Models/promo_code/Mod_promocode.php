<?php

namespace App\Models\promo_code;

use Illuminate\Database\Eloquent\Model;

class Mod_promocode extends Model
{
    protected $table = 'promocode';
    protected $casts = [
        'discount_percentage' => 'decimal',
    ];
}