<?php

namespace App\Http\Controllers\admin\joined_us;

use App\Models\entrepreneur_prodct\Mod_entr_products;
use App\Models\products_categories\Mod_category;
use App\Models\vendor_application\Mode_vendor_application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class joinedController extends Controller
{
    public function entrpreneur_index($value)
    {

        return view('pages.entrpreneur_register',
            [
                'value'    => $value
            ]);
    }
    public function entr_dashboard()
    {
        $entr_products                     = Mod_entr_products::all();
        return view('pages.entrprenuer_dashboard',[
            'entr_products' => $entr_products
            ]);
    }
    public function vendr_dashboard()
    {
        return view('pages.vendr_dashboard');
    }
    public function add_product()
    {
        $category                          = Mod_category::all();
        $entr_products                     = Mod_entr_products::all();
        return view('pages.add_entr_product',[
            'category'     => $category,
        ]);

    }
    public function save_product(Request $request)
    {
        $request->validate([
           'featured_image' => 'image',
            'image1'        =>  'image',
            'image2'        =>  'image',
        ]);
        $input                                   = $request->all();
        $entr_products                           = new Mod_entr_products();
        $entr_products->user_id                  = Auth::guard('entr_vendr')->user()->id;
        $entr_products->category                 = $input['product_category'];
        $entr_products->product_name             = $input['product_name'];
        $entr_products->type                     = $input['product_type'];
        $entr_products->quantity                 = $input['quantity'];
        $entr_products->price                    = $input['price'];
        $entr_products->sal_new                  = $input['sale_new'];
        $entr_products->replace_sale             = $input['replace_sell'];
        $entr_products->description              = $input['product_description'];
        if($request->hasFile('featured_image')){

            $file =$request->file('featured_image');
            $uniqueFileName = $file->getClientOriginalName() ;
            $request->file('featured_image')->move(public_path('images\entr_products'),$uniqueFileName);
            $entr_products->featured_image           = $uniqueFileName;
        }
        if ($request->hasFile('image1'))
        {
            $file      = $request->file('image1');
            $filename  = $file->getClientOriginalName();
            $request->file('image1')->move(public_path('images\entr_products'),$filename);
            $entr_products->image1                    = $filename;
        }
        if ($request->hasFile('image2'))
        {
            $file      = $request->file('image2');
            $filename  = $file->getClientOriginalName();
            $request->file('image2')->move(public_path('images\entr_products'),$filename);
            $entr_products->image2                   = $filename;
        }
        $entr_products->status                    = 0;
        $entr_products->save();
        return response()->json("Product has been added successfully");
    }
    public function apply()
    {
        $category                          = Mod_category::all();
        return view('pages.vendor_application',
            [
                'category' => $category
            ]);
    }
    public function save_apply(Request $request)
    {
        $input                          = $request->all();
        $application                    = new Mode_vendor_application();
        $application->user_id           = Auth::guard('entr_vendr')->user()->id;
        $application->title             = $input['title'];
        $application->category          = $input['product_category'];
        $application->type              = $input['product_type'];
        $application->description       = $input['description'];
        $application->status            = 0;
        $application->save();
        return response()->json("Your Application Has been sent successfuly");
    }

}
