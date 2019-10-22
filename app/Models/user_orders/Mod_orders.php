<?php

namespace App\Models\user_orders;

use App\Models\delivery_info\Mod_deliveryinfo;
use App\Models\ordered_products\Mod_ordered_products;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Mod_orders extends Model
{
    protected $table = 'orders';
   protected $fillable   = [
    'user_id', 'status', 'total_amount', 'sub_amount', 'vat', 'delivery_fee', 'delivery_info',  'cart_id','cart_id',
  ];

   public function getdeliveryaccc()
   {
       return $this->belongsTo(Mod_deliveryinfo::class,'delivery_info');
   }
   public function getussser()
   {
       return $this->belongsTo(User::class,'user_id');
   }

    public function order_has_many_reorders(){
       return $this->hasMany('App\mymodels\reorder', 'order_id', 'id');
    }
    }
