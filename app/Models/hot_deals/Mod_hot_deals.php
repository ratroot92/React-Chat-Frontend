<?php

namespace App\Models\hot_deals;

use App\Models\replace_items\Mod_Replace;
use Illuminate\Database\Eloquent\Model;

class Mod_hot_deals extends Model
{
    protected $table = 'hot_deal';

    public function getProdUct()
    {
        return $this->belongsTo(Mod_Replace::class,'product_id');
    }
}
