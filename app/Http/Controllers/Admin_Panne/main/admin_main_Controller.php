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
use App\Models\products_categories\Mod_category;
use App\mymodels\Mod_Subcategory;
use App\mymodels\product_model;
use Validator;
use Illuminate\Support\Facades\Input;

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
        $categories=Mod_category::all();
        return view('admin.pages.stores.index',compact('categories'));
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
    {                    $category    =Mod_category::all();
                         $subcategory =Mod_Subcategory::all();
                         $products    =product_model::all();
        return view('admin.pages.products.index',
            ['products'=>$products,
             'category'=>$category,
             'subcategory'=>$subcategory
            ]);
    }
    public function add_product()
    {
                        $category   =Mod_category::all();
                        $subcategory =Mod_Subcategory::all();

        return view('admin.pages.products.add', [
                'category'    => $category,
                'subcategory' => $subcategory
            ]);
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


//start of add category 
public function addcategory(Request $request){

        $validator              = Validator::make($request->all(), [

        'name'                  =>'required|min:3|max:30',
        'description'           =>'required|min:10|max:400',
        'status'                =>'required|integer',
        'file'                  =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                                                                    ]);



  if ($validator->fails()) {
        return redirect('admin/add_store')
                ->withErrors($validator)
                ->withInput();

           

            }

else{

//dd($request->all());
    if($request->file('file')){
        $image                  =$request->file('file');
        $name                   = time().'.'.$image->getClientOriginalExtension();
        $destinationPath        = public_path('/AdminImages/Categories_Images/');
        $databse_path ='http://chameleon.mediabloo.com/public/AdminImages/Categories_Images/'.$name;
        $destination            =$image->move($destinationPath, $name);




 $new_category                          =new Mod_category;
 $new_category->category_name           =$request->input('name');
 $new_category->category_description    =$request->input('description');
 $new_category->category_image          =$databse_path;
 $new_category->category_status         =$request->input('status');
                                        $new_category->save();

if($new_category){

 return redirect('admin/add_store')->with('success','Category added successfully ');
}


else{
    return redirect()->route('admin/add_store')->with('error','Operation failed  ');
}

}




//has file else 
else{
    return redirect()->route('admin/add_store')->with('error','Operation failed  ');
}



}
}// end  of function 




public function get_cat_for_edit($id){


$get_cat            = DB::table('categories')
                    ->where('id',$id)
                    ->first();

        return response()->json($get_cat);
}






public function edit_cat(Request $request){


$validator                  = Validator::make($request->all(), [

        'name'              =>'required|min:3|max:30',
        'description'       =>'required|min:10|max:400',
        'status'            =>'required|integer',
                                                                ]);



  if ($validator->fails()) {
        return redirect('admin/stores')
                ->withErrors($validator)
                ->withInput();
                            }

else{
$id                 =$request->input('id');
$name               =$request->input('name');
$description        =$request->input('description');
$status             =$request->input('status');


 $updated_record          
 = DB::table('categories')->where('id',$id)
 ->update([     'category_name'            =>$name,
                'category_description'     =>$description,
                'category_status'          =>$status
            ]);
 


if($updated_record){

 return redirect('admin/stores')->with('success','Category added successfully ');
}

else{
 return redirect('/admin/stores')->with('success','No changes !   ');
}
}
}//end of edit cat function 

public function del_cat($id){

$deleted_category=Mod_category::whereId($id)->delete();
if($deleted_category){
return redirect('admin/stores')->with('success','Category deleted successfully');
}
else{
 return redirect('admin/stores')->with('success','Operation failed ');   
}
}




public function insert_product(Request $request){

        $validator              = Validator::make($request->all(), [

        // 'name'                  =>'required',
        // 'description'           =>'required',
        // 'status'                =>'required',
        // 'price'                 =>'required',
        // 'quantity'              =>'required',
        // 'weight'                =>'required',
        // 'sound'                 =>'required',
        // 'tooltype'              =>'required',
        // 'category'              =>'required',
        // 'subcategory'           =>'required',
        // 'file'                  =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                                                                    ]);


  if ($validator->fails()) {
        return redirect('/admin/add_product')
                ->withErrors($validator)
                ->withInput();

           

            }

else{
    $last_product_id=0;
    $last_product = product_model::orderBy('created_at', 'desc')->first();
            if ($last_product != null) {
                $last_product_id              = $last_product->id + 1;
            } else {
                $last_product_id              = 1;
            }




    if($request->file('file')){
        $image                  =$request->file('file');
        $name                   = time().'.'.$image->getClientOriginalExtension();
        $destinationPath        = public_path('/AdminImages/Product_images/');
         $databse_path ='http://chameleon.mediabloo.com/public/AdminImages/Sub_Categories_Images/'.$name;
        $destination            =$image->move($destinationPath, $name);




 $new_product                                 =new product_model;
 $new_product->id                             =$last_product_id;
 $new_product->name                           =$request->input('name');
 $new_product->price                          =$request->input('price');
 $new_product->weight                         =$request->input('weight');
 $new_product->sound_power                    =$request->input('sound');
 $new_product->category                       =$request->input('category');
 $new_product->subcategory                    =$request->input('subcategory');
 $new_product->description                    =$request->input('description');
 $new_product->featured_image                 =$databse_path;
 $new_product->status                         =$request->input('status');
 $new_product->quantity                       =$request->input('quantity');
 $new_product->model                          =$request->input('model');
 $new_product->tool_type                      =$request->input('tooltype');
$new_product->manufacturer                  =$request->input('manufacturer');
$new_product->save();
if($new_product){

 return redirect('/admin/add_product')->with('success','Product added successfully ');
}


else{
    return redirect('/admin/add_product')->with('error','Operation failed  ');
}

}




//has file else 
else{
    return redirect('/admin/add_product')->with('error','Operation failed  ');
}



} 
}


public function get_pro_for_edit($id){

$get_product= DB::table('product_models')
 ->where('id',$id)
 ->first();




return response()->json($get_product);


}





public function edit_product(Request $request){
 $validator              = Validator::make($request->all(), [

        // 'name'                  =>'required',
        // 'description'           =>'required',
        // 'status'                =>'required',
        // 'price'                 =>'required',
        // 'quantity'              =>'required',
        // 'weight'                =>'required',
        // 'sound'                 =>'required',
        // 'tooltype'              =>'required',
        // 'category'              =>'required',
        // 'subcategory'           =>'required',
        // 'file'                  =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                                                                    ]);


  if ($validator->fails()) {
        return redirect('/admin/products')
                ->withErrors($validator)
                ->withInput();

           

            }

else{
   

        
$id                               =$request->input('id');
$name                             =$request->input('name');
$price                            =$request->input('price');
$weight                           =$request->input('weight');
$sound                            =$request->input('sound');      
$category                         =$request->input('category');
$subcategory                      =$request->input('subcategory');
$description                      =$request->input('description');
$status                           =$request->input('status');
$quantity                         =$request->input('quantity');      
$tooltype                         =$request->input('tooltype');
$manufacturer                     =$request->input('manufacturer');
$model                            =$request->input('model');




$updated_record          =DB::table('product_models')->where('id',$id)
                            ->update([
                            'name'                 =>$name,
                            'price'                =>$price,
                            'weight'               =>$weight,
                             'sound_power'         =>$sound,
                            'category'             =>$category,
                            'subcategory'          =>$subcategory,
                            'description'          =>$description,
                            'status'               =>$status,
                            'quantity'             =>$quantity,
                            'model'                =>$model,
                            'tool_type'            =>$tooltype,
                            'manufacturer'         =>$manufacturer,
                           
                            ]);  





if($updated_record){

 return redirect('/admin/products')->with('success','Product added successfully ');
}


else{
    return redirect('/admin/products')->with('success','No changes !   ');
}






}
}//end of function 





public function del_pro($id){
  
  
  $deleted_category             =product_model::whereId($id)->delete();
  if($deleted_category)             {

  return redirect('/admin/products')->with('success','Subcategory deleted successfully');

                                    }

  else                              {
  return redirect('/admin/products')->with('success','Operation failed ');   
                                    }
  
  
}
}