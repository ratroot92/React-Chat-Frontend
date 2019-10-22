<?php

namespace App\Models\most_popular_products;

use App\Models\replace_items\Mod_Replace;
use Illuminate\Database\Eloquent\Model;

class Mod_popular_products extends Model
{
    protected $table = 'most_popular_products';

    public function getProduct()
    {
        return $this->belongsTo(Mod_Replace::class,'product_id');
    }
}
