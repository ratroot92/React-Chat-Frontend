<?php

namespace App\Models\special_products;

use App\Models\replace_items\Mod_Replace;
use Illuminate\Database\Eloquent\Model;

class Mod_special_products extends Model
{
    protected $table = 'special_products';

    public function getProducT()
    {
        return $this->belongsTo(Mod_Replace::class,'product_id');
    }
}
