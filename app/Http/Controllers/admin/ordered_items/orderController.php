<?php

namespace App\Http\Controllers\admin\ordered_items;

use App\Models\delivery_info\Mod_deliveryinfo;
use App\Models\ordered_products\Mod_ordered_products;
use App\Models\shopping_cart\Mod_Shoppingcart;
use App\Models\user_orders\Mod_orders;
use App\Models\user_payments\Mod_Payments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class orderController extends Controller
{
   public function invoice(Request $request,$discount)
   {

       $today_date  = Carbon::now()->toDateString();

       $items          = $request->session()->get('cart');

       $delivery_info  = Mod_deliveryinfo::where('user_id','=',Auth::user()->id)
                                         ->orderby('id','DESC')
                                         ->first();

       $payment_info    = Mod_Payments::where('user_id','=',Auth::user()->id)
                                        ->where('status','=',1)
                                        ->get();
       $invoice          = Mod_orders::orderby('id','DESC')
                                     ->pluck('invoice_no')->first();

       $orderID           = Mod_orders::where('user_id',Auth::user()->id)
                                       ->orderby('id','DESC')
                                        ->pluck('invoice_no')->first();
       if($orderID=="")
       {
           $orderID = 1;
       }
       else
       {
           $orderID += 1;
       }
       if($invoice=="")
       {
           $invoice_no = 1;
       }
       else
       {
           $invoice_no = $invoice+1;
       }
           return view('pages.invoice',
               [
                   'items' => $items,
                   'delivery_info' => $delivery_info,
                   'payment_info' => $payment_info,
                   'today_date' => $today_date,
                   'discount' => $discount,
                    'orderID' => $orderID,
                   'invoice_no' => $invoice_no
               ]);
       }

   public function post_orders(Request $request)
   {
       $input                     = $request->all();
       $order                     = new Mod_orders();
       $order->user_id            = Auth::user()->id;
       $order->invoice_no         = $input['invoice_no'];
       $order->due_amount         = $input['due_amount'];
       $order->delivery_info      = $input['delivery_ifo'];
       $order->status             = 0;
       $order->save();

       $orderID                    = Mod_orders::orderby('id','DESC')->pluck('id')->first();
       $shopping_cart                       = $request->session()->get('cart');
       //$shopping_cart             = Mod_Shoppingcart::where('user_id','=',Auth::user()->id)->get();

       // Copying products from shopping cart to ordered products table
       foreach ($shopping_cart->items as $shopping_cart_row)
       {
           $ordered_product               = new Mod_ordered_products();
           $ordered_product->user_id      = Auth::user()->id;
           $ordered_product->order_id     = $orderID;
           $ordered_product->product_id   = $shopping_cart_row['item']['id'];
           $ordered_product->total_items  = $shopping_cart_row['qty'];
           $ordered_product->total_amount = $shopping_cart_row['price'];
           $ordered_product->status       = 1;
           $ordered_product->save();
       }

      // Empty shopping cart
       $request->session()->flash('cart');
       $cart1   = $request->session()->get('cart');
       if ($cart1==true)
       {
           return response()->json(1);
       }
       else{
           return response()->json(0);
       }

   }

   public function my_orders()
   {
       $orders            = Mod_orders::where('user_id','=',Auth::user()->id)->get();

       return view('pages.orders',
           [
               'orders'   => $orders
           ]);
   }
   public function view_order($order_id)
   {
       $ordered_products       = Mod_ordered_products::where('user_id','=',Auth::user()->id)
                                                      ->where('order_id','=',$order_id)->get();
       $orders                 = Mod_orders::where('id',$order_id)->first();
       $payment_info           = Mod_Payments::where('user_id','=',Auth::user()->id)
                                               ->where('status','=',1);
       return view('pages.view_orders',
           [
               'ordered_products'   => $ordered_products,
               'orders'             => $orders,
               'payment_info'       => $payment_info,
           ]);
   }
}
