<?php

namespace App\Models\products_categories;

use Illuminate\Database\Eloquent\Model;

class Mod_category extends Model
{
	 protected $table = 'categories';
	 protected $fillable = [
        'category_name', 'category_description', '	category_status',
    ];


    public function subcategories(){
    	return $this->hasMany('App\mymodels\Mod_Subcategory', 'parent_category', 'id');
    }

}
     

