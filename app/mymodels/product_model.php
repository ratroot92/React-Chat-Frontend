<?php

namespace App\mymodels;

use Illuminate\Database\Eloquent\Model;

class product_model extends Model
{
	protected $table = 'product_models';
    protected $primaryKey = 'id';
     protected $fillable = [
         'name', 'price','detail', 'category','subcategory','tool_type', 'manufacturer','description', 'model','weight','sound_power','quantity','featured_image', 'status',
    ];
    public function productimages(){
    	 return $this->hasMany('App\mymodels\image_model', 'product_id', 'id');
    }
}
