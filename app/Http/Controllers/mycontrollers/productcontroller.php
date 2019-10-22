<?php

namespace App\Http\Controllers\mycontrollers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\mymodels\category_model;
use App\mymodels\product_model;
use Validator;
class productcontroller extends Controller
{
    public function index (){
        $allcategories=category_model::all();
    	$last_product_id=0;
    	 $last_product = product_model::orderBy('created_at', 'desc')->first();
            if ($last_product != null) {
                $last_product_id              = $last_product->id + 1;
            } else {
                $last_product_id              = 1;
            }


    	$all_products=category_model::all();
    	return view ('admin/pages/n_products/products',compact('last_product_id','allcategories'));
    	
    }



public function renderproducttable(){
    $all_products=product_model::all();
return view ('admin.pages.n_products.product_table',compact('all_products'));
}








    public function insert(Request $request){



    	 $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'category' => 'required',
            'desc' => 'required',
            'quantity' => 'required',
            'saleprice' => 'required',
            'status' => 'required|integer',
            'purchaseprice' => 'required',
            'images' => 'required'
        ]);
        if ($validator->passes()) {
            $last_product = product_model::orderBy('created_at', 'desc')->first();
            if ($last_product != null) {
                $last_product_id              = $last_product->id + 1;
            } else {
                $last_product_id              = 1;
            }

            $input = $request->all();
            $this->validate($request, [
                // 'uploads' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = time() . "_." . $extension;
                    //$filename = $upload->getClientOriginalName();
                    $path = $image->move(public_path('products/Images'), $filename);

                    $file = new image_model;
                    $file->product_id = $last_product_id;
                    $file->src = $path;
                    $file->save();
                }
            }
            $product = new product_model;
            $product->id = $last_product_id;
            $product->name = $input['name'];
            $product->category = $request->input('category');
            $product->description = $input['desc'];
            $product->quantity = $input['quantity'];
            $product->pprice = $input['purchaseprice'];
            $product->sprice = $input['saleprice'];
            $product->featured_image = $input['saleprice'];
             $product->status = $input['status'];
            $product->save();
           // $product = Item::orderBy('created_at', 'desc')->first();
            return response()->json(array(
                'success' => 'Prdouct saved to database ',

            ));
        }

        else{
            return response()->json(['error' => $validator->errors()->all()]);
        }
    }
}
