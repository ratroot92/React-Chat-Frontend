<?php

namespace App\Models\ordered_products;

use App\Models\replace_items\Mod_Replace;
use App\Models\user_orders\Mod_orders;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Mod_ordered_products extends Model
{
    protected $table = 'ordered_products';

    public function getprodct()
    {
        return $this->belongsTo(Mod_Replace::class,'product_id');
    }

    public function getOrder()
    {
        return $this->belongsTo(Mod_orders::class,'order_id');
    }
    public function getUser()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
