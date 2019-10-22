<?php

namespace App\Models\products_categories;

use Illuminate\Database\Eloquent\Model;

class Mod_category extends Model
{
	 protected $table = 'categories';
	 protected $fillable = [
        'category_name', 'category_description', '	category_status',
    ];

}
     

