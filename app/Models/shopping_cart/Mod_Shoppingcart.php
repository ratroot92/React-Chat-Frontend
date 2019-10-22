<?php

namespace App\Models\shopping_cart;

use App\Models\replace_items\Mod_Replace;
use Illuminate\Database\Eloquent\Model;

class Mod_Shoppingcart extends Model
{
    protected $table = 'shoppingcart';

    public function getacc()
    {
        return $this->belongsTo(Mod_Replace::class,'product_id');
    }
}
