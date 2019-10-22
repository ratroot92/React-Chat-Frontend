<?php

namespace App\Http\Controllers\admin\whishlist;

use App\Models\whishlist\Mod_Whishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class whishlistController extends Controller
{
    public function add_items($product_id)
    {
        $whishlist              = new Mod_Whishlist();
        $whishlist->user_id     = Auth::user()->id;
        $whishlist->product_id  = $product_id;
        $whishlist->status      = 1;
        $whishlist->save();
        $whishlist              = Mod_Whishlist::where('user_id','=',Auth::user()->id)->get();
        $total_products         = count($whishlist);
        return response()->json($total_products);
    }
}
