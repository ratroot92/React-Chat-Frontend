<?php

namespace App\Http\Controllers\admin\main;

use App\Models\best_selling\Mod_best_selling;
use App\Models\featured_products\Mod_featured_products;
use App\Models\hot_deals\Mod_hot_deals;
use App\Models\most_popular_products\Mod_popular_products;
use App\Models\replace_items\Mod_Replace;
use App\Models\best_sale_of_week\Mod_bestsale;
use App\Models\shopping_cart\Mod_Shoppingcart;
use App\Models\special_products\Mod_special_products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Session;
class mainController extends Controller
{
    public function index(Request $request)
    {
        $products            = Mod_Replace::all();
        $mobiles             = Mod_Replace::where('product_category','=','1')->get();
        $books               = Mod_Replace::where('product_category','=','2')->get();
        $furniture           = Mod_Replace::where('product_category','=','3')->get();
        $best_products       = Mod_bestsale::where('status','=',1)->get();
        $hot_deal            = Mod_hot_deals::where('status','=',1)->orderby('id','DESC')->first();
        $best_selling        = Mod_best_selling::where('status','=',1)->get();
        $featured_products   = Mod_featured_products::where('status','=',1)->get();
        $special_products    = Mod_special_products::where('status','=',1)->get();
        $popular_products    = Mod_popular_products::where('status','=',1)->get();
        $cart                = $request->session()->get('cart');
        return view('pages/index',
            [
                'products' => $products,
                'mobiles' => $mobiles,
                'books'  => $books,
                'furniture' => $furniture,
                'best_products'       => $best_products,
                 'hot_deal'           => $hot_deal,
                 'best_selling'       => $best_selling,
                 'featured_products'  => $featured_products,
                 'special_products'   => $special_products,
                 'popular_products'   => $popular_products,
                'cart'     => $cart

            ]);

    }


    public function about_us()
    {
        return view('pages.about_us');
    }
    public function contact_us()
    {
        return view('pages.contact_us');
    }

    public function Old_Shopping_Grid($category)
    {

            $product           = Mod_Replace::where('product_type','=',0)
                                               ->where('product_category','=',$category)
                                               ->where('status','=',1)
                                               ->get();

        return view('pages.shop_grid',[
            'product' => $product
        ]);
    }

    public function Sign_Up()
    {
        return view('pages.account_page');
    }

    public function Quick_View($productid)
    {
        $product          = Mod_Replace::where('id','=',$productid)->first();
        return view('pages.quick_view',
            [
                'product'  => $product
            ]);
    }
}
