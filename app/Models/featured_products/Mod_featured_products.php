<?php

namespace App\Models\featured_products;

use App\Models\replace_items\Mod_Replace;
use Illuminate\Database\Eloquent\Model;

class Mod_featured_products extends Model
{
    protected $table = 'featured_products';

    public function getProducT()
    {
        return $this->belongsTo(Mod_Replace::class,'product_id');
    }
}
