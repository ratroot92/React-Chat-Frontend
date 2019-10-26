<?php

namespace App\mymodels;

use Illuminate\Database\Eloquent\Model;

class Mod_Subcategory extends Model
{
    

 protected $table = 'subcategories';
	 protected $fillable = [
        'name', 'image', 'status','parent-category','description'
    ];



    public function category(){
    	 return $this->belongsTo('App\Models\products_categories\Mod_category','parent_category');
    }
}
