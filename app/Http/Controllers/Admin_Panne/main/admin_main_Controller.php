<?php

namespace App\Http\Controllers\Admin_Panne\main;

use App\Models\ordered_products\Mod_ordered_products;
use App\Models\replace_items\Mod_Replace;
use App\Models\user_orders\Mod_orders;
use App\Models\user_payments\Mod_Payments;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class admin_main_Controller extends Controller
{
    public function main_index()
    {
        //total costumer
        $users         = User::all();
        $costumer      = count($users);
        //total orders
        $torders       = count(Mod_orders::all());

        // close deals
        $replaceitems   = count(Mod_Replace::where('status','=',1)->get());
        $oredrs         = count(Mod_orders::where('status','=',1)->get());
        $close_deals    = $replaceitems + $oredrs;

        // active deals
        $activedeals   = (count(Mod_Replace::all())+count(Mod_orders::all()))-$close_deals;

        //recent replacement request
        $replace_requests                 = Mod_Replace::where('product_type','=',1)
                                                        ->get();

        // recent orders request

        $orders_request                  = Mod_orders::where('status','=',0)->get();
        return view('admin.pages.index',
            [
                'costumer'         => $costumer,
                'torders'          => $torders,
                'close_deals'      => $close_deals,
                'activedeals'      => $activedeals,
                'replace_requests' => $replace_requests,
                'orders_request'   => $orders_request
            ]);
    }
    // stores
    public function store_index()
    {
        return view('admin.pages.stores.index');
    }
    public function add_store()
    {
        return view('admin.pages.stores.add');
    }
    // Drivers
    public function drivers()
    {
        return view('admin.pages.drivers.index');
    }
    public function add_driver()
    {
        return view('admin.pages.drivers.add');
    }
    public function driver_schedule()
    {
        return view('admin.pages.drivers.driver_schedule');
    }
    public function products()
    {
        return view('admin.pages.products.index');
    }
    public function add_product()
    {
        return view('admin.pages.products.add');
    }
    public function orders()
    {
        $orders                  = Mod_orders::where('status','=',1)->get();
        return view('admin.pages.orders.index',[
            'orders'    => $orders
        ]);
    }
    //payments
    public function payments()
    {
        return view('admin.pages.payments.index');
    }
    //sales
    public function sales()
    {
        return view('admin.pages.sales.index');
    }
    //account settings
    public function settings()
    {
        return view('admin.pages.settings.index');
    }
    // View replacement request
    public function view_replacement_request($id)
    {
        $product       = Mod_Replace::findorfail($id);
        return view('admin.pages.replacement.view_replace',
            [
                'product'  => $product
            ]);
    }
    public function delete_replacement_request($id)
    {
        //$product     = Mod_Replace::findorfail($id);
       // $product->delete();
        $product     = Mod_Replace::where('product_type','=',1)
                                      ->with('getacc')->with('catagacc')->get();
        return response()->json($product);
    }
    public function approve_replacement_request($id)
    {
        $product           = Mod_Replace::findorfail($id);
        $product->status   = 1;
        $product->save();
        $products          = Mod_Replace::where('product_type','=',1)
                                          ->with('getacc')->with('catagacc') ->get();
        return response()->json($products);
    }

    // Recent orders
    public function view_order($id)
    {
        $orders                   = Mod_orders::findorfail($id);
        $ordered_products         = Mod_ordered_products::where('order_id','=',$id)->get();
        $payment_info             = Mod_Payments::where('user_id','=',$orders->user_id)->get();
        return view('admin.pages.orders.view_order',
            [
                'ordered_products'  => $ordered_products,
                'payment_info'      => $payment_info,
                'orders'            => $orders
            ]);
    }
    public function deposit_amount($id)
    {
        $order         = Mod_orders::findorfail($id);
        return view('admin.pages.orders.deposit_amount',
            [
                'order' => $order
            ]);
    }
    public function update_amount(Request $request,$id)
    {
        $order         = Mod_orders::findorfail($id);
        $input         = $request->all();
        $order->paid_amount  = $input['deposit_amount'];
        $order->save();
         if(($order->paid_amount)>=($order->due_amount))
         {
             $order->status = 1;
             $order->save();
         }
        $order         = Mod_orders::where('status','=',1)->with('getussser')->with('getdeliveryaccc')->get();
        return response()->json($order);

    }
}
