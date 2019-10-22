<?php

namespace App\Models\entrepreneur_prodct;

use App\entr_vendr;
use App\Models\products_categories\Mod_category;
use Illuminate\Database\Eloquent\Model;

class Mod_entr_products extends Model
{
    protected $table     = 'entr_products';

    public function user()
    {
        return $this->belongsTo(entr_vendr::class,'user_id');
    }
    public function catagacc()
    {
        return $this->belongsTo(Mod_category::class,'category');
    }
}
