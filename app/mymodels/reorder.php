<?php

namespace App\mymodels;

use Illuminate\Database\Eloquent\Model;

class reorder extends Model
{
    protected $table='reorders';
    protected $fillable=['order_id','user_id','cart_id','total_amount','deliver_address','payment_type'];

    public function reorder(){
    	return $this->belongsTo('App\Models\user_orders\Mod_orders');
    }
}
