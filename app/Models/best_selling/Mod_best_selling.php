<?php

namespace App\Models\best_selling;

use App\Models\replace_items\Mod_Replace;
use Illuminate\Database\Eloquent\Model;

class Mod_best_selling extends Model
{
  protected $table = 'best_selling';

  public function getProdUCt()
  {
      return $this->belongsTo(Mod_Replace::class,'product_id');
  }
}
