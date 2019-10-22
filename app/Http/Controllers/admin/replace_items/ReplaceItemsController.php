<?php

namespace App\Http\Controllers\admin\replace_items;

use App\Models\products_categories\Mod_category;
use App\Models\replace_items\Mod_Replace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReplaceItemsController extends Controller
{
    public function index()
    {
        $category          = Mod_category::all();
        return view('pages.replace_items',
            [
                'category' => $category
            ]);
    }
    public function save(Request $request)
    {
      $replace                        = new Mod_Replace();
      $input                          = $request->all();
      $replace->user_id               = Auth::user()->id;
      $replace->product_category      = $input['product_category'];
      $replace->product_name          = $input['product_name'];
      $replace->product_price         = $input['product_price'];
      $replace->product_quantity      = $input['product_quantity'];
        if($request->hasFile('file')){

            $filename=$request->file->getClientOriginalName();
            $request->file->move(public_path('images'),$filename);
        }
        $replace->product_image       = $filename;
       $replace->product_type          = $input['product_type'];
        $replace->sal_new              = $input['sale_new'];
        $replace->replace_sell         = $input['replace_sell'];
        $replace->status               = 0;
      $replace->product_description    = $input['product_description'];


        $replace->save();
        $request->session()->flash('message', 'Product has been added successfully.');
        $request->session()->flash('message-type', 'success');
        return redirect('/index');
    }
}
