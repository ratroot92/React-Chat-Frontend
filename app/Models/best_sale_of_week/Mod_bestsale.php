<?php

namespace App\Models\best_sale_of_week;

use App\Models\replace_items\Mod_Replace;
use Illuminate\Database\Eloquent\Model;

class Mod_bestsale extends Model
{
    protected  $table = 'best_sale_of_week';

    public function getproduct()
    {
        return $this->belongsTo(Mod_Replace::class,'product_id');
    }
}
