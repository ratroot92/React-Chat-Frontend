<?php

namespace App\Http\Controllers\admin\shopping_cart;

use App\cart;
use App\Models\replace_items\Mod_Replace;
use App\Models\shopping_cart\Mod_Shoppingcart;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Session;
class ShoppingCartController extends Controller
{
    public function Cart_view(Request $request)
    {
        $cart                = $request->session()->get('cart');
        return view('pages.shopping_cart',[
                'cart' => $cart
        ]);
    }
    public function add_to_cart(Request $request,$product_id)
    {
        $prodcuts            = Mod_Replace::findorfail($product_id);
        $oldcart             = Session::has('cart') ? Session::get('cart') : null;
        $cart                = new cart($oldcart);
        $cart->add($prodcuts,$product_id,$request->quantity);
        $request->session()->put('cart',$cart);
       $cart1 = $request->session()->get('cart');
       return response()->json($cart1);
    }

    public function delete_item(Request $request,$id)
    {
       /* $oldcart             = Session::has('cart') ? Session::get('cart') : null;
        $cart                  = new cart($oldcart);
        $value                 = $cart->deleteItem($id);
        dd($value);*/
       $items                  = Session::get('cart',[]);
       foreach ($items->items as $item_row)
       {
           if ($item_row['item']['id']==$id)
           {
               $index         = array_search($item_row,$items->items);
             unset($items->items[$index]);
               $items->total_price     = $items->total_price - $item_row['price'];
               $items->total_quantity  = $items->total_quantity - ($item_row['qty']);
           }
       }
       //Session::set('items',$items);
      return response()->json($items);
    }
    public function empty_cart(Request $request)
    {
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
}
