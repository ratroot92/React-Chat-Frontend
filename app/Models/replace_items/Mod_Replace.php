<?php

namespace App\Models\replace_items;

use App\Models\products_categories\Mod_category;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Mod_Replace extends Model
{
    protected $table = 'replaceitems';

    public function catagacc()
    {
        return $this->belongsTo(Mod_category::class,'product_category');
    }

    public function getacc()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
