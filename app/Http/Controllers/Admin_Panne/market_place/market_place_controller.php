<?php

namespace App\Http\Controllers\Admin_panne\market_place;

use App\Models\admin\Companies\Mod_company;
use App\Models\delivery_info\Mod_deliveryinfo;
use App\Models\entrepreneur_prodct\Mod_entr_products;
use App\Models\ordered_products\Mod_ordered_products;
use App\Models\replace_items\Mod_Replace;
use App\Models\shopping_cart\Mod_Shoppingcart;
use App\Models\user_orders\Mod_orders;
use App\Models\user_payments\Mod_Payments;
use App\Models\whishlist\Mod_Whishlist;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class market_place_controller extends Controller
{
    public function index()
    {
        $registerd_user            = User::all();
        $all_orders                = Mod_orders::all();
        $old_products              = Mod_Replace::where('product_type','=',0)->get();
        $company                   = Mod_company::all();
        $new_products              = Mod_Replace::where('product_type','=',1)->get();
        return view('admin.pages.marketplace',
            [
                'registerd_user'  => $registerd_user,
                'all_orders'      => $all_orders,
                'old_products'    => $old_products,
                'company'         => $company,
                'new_products'    => $new_products
            ]);
    }
    public function delete_user($id)
    {
        /*$user               = User::findorfail($id);
        $products           = Mod_Replace::where('user_id','=',$id)->delete();
        $orders             = Mod_orders::where('user_id','=',$id)->delete();
        $ordered_products   = Mod_ordered_products::where('user_id','=',$id)->delete();
        $shopping_cart      = Mod_Shoppingcart::where('user_id','=',$id)->delete();
        $payment_info       = Mod_Payments::where('user_id','=',$id)->delete();
        $delivery_info      = Mod_deliveryinfo::where('user_id','=',$id)->delete();
        $whishlist          = Mod_Whishlist::where('user_id','=',$id)->delete();
        $user->delete();*/
        $user               = User::all();
        return response()->json($user);
    }
   public function entraprenuer()
   {
       $entr_products                = Mod_entr_products::all();
       return view('admin.pages.Entrepreneur1.entrepreneur',
           [
               'entr_products'     => $entr_products
           ]);
   }
   public function view_entr($id)
   {
       $entr                   = Mod_entr_products::findorfail($id);
       return view('admin.pages.Entrepreneur1.view_entr',
           [
               'entr'     => $entr
           ]);
   }
   public function approve_entr($id)
   {
       $entr                   = Mod_entr_products::findorfail($id);
       $entr->status=1;
       $entr->save();
       return redirect('/admin_marketplace');
   }


}
