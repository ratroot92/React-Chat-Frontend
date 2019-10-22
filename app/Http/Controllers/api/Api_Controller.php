<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\special_products\Mod_special_products;
use App\Models\products_categories\Mod_category;
use App\mymodels\product_model;
use App\mymodels\add_to_cart;
use App\mymodels\cart_items;
use Illuminate\Support\Facades\Auth;
use App\Models\shopping_cart\Mod_Shoppingcart;
use App\Models\user_orders\Mod_orders;
use App\mymodels\reorder;
use Validator;
use DB;
class Api_Controller extends Controller
{
public function register(Request $request)
{
$validator = Validator::make($request->all(), [
'name' => 'required',
'email' => 'required|email',
'password' => 'required',
// 'c_password' => 'required|same:password',
]);
if ($validator->fails()) {
return response()->json(['error'=>$validator->errors()], 401);
}
$input = $request->all();
$input['password'] = bcrypt($input['password']);
$user = User::create($input);
$success['token'] =  $user->createToken('MyApp')-> accessToken;
$success['name'] =  $user->name;

$name=$input['name'];
$password=$input['password'];
$registered_user = DB::table('users')
->select(DB::raw('select * form users'))
->where('name',$name)
->where('password',$password)
->get();
// return response()->json(['success'=>$success], $this->successStatus);
return response()->json(['message'=>'Successfull Registertion','status'=>'200','object'=>$registered_user]);
}

/**
* details api
*
* @return \Illuminate\Http\Response
*/


public function details()
{
$user = Auth::user();
return response()->json(['success' => $user], $this-> successStatus);
}

//kabeer
public function signup (Request $request){
$validator = Validator::make($request->all(), [
'email'=>'unique:users,email',
]);

if ($validator->passes()) {
$newUser=new User;
$newUser->fname=$request->input('fname');
$newUser->lname=$request->input('lanme');
$newUser->post_code=$request->input('post_code');
$newUser->address=$request->input('address');
$newUser->email=$request->input('email');
$newUser->contact_no=$request->input('contact_no');
$newUser->password=$request->input('password');
$newUser->city=$request->input('city');
$newUser->image=$request->input('image');

$check=$newUser->save();
if($check){
return response()->json(['message'=>'Successfull SignUp','status'=>'200','object'=>$newUser]);
}
else{
return response()->json(['message'=>'Failed SignUp','status'=>'401']);
}

}
else{
return response()->json(['message'=>'Email Already Exsist ','status'=>'401'], 401);
}
}

//kabeer



public function listSpecialProducts(){

$products=product_model::all();//send products that are special // status 1
$category=Mod_category::all();
if(count($products)>0){

return response()->json(['message'=>'Successfull','status'=>'200','object'=>$products,'category_object'=>$category]);
}
else{
return response()->json(['message'=>'Failed Query','status'=>'401']);
}
}




public function listProductsByCategory($id){


$category=  Mod_category::whereId($id)->first();

if($category){

$category_name=$category->category_name;
$allproducts_by_category = DB::table('product_models')
->where('tool_type', '=', $category_name)
->get();

if(count($allproducts_by_category)>0){
return response()->json(['message'=>'Successfull','status'=>'200','object'=>$allproducts_by_category]);
}
else{
return response()->json(['message'=>'No Prodcuts For This Category','status'=>'401']);
}

}
else{
return response()->json(['message'=>'Failed Query','status'=>'401']);
}


}


public function productDetail($id){



$validator = Validator::make($request->all(), [
'product_id' => 'required|exists:product_models,id',          // 'c_password' => 'required|same:password',
]);


if ($validator->passes()) {

$product=product_model::whereId($id)->first();


if($product){
return response()->json(['message'=>'Successfull','status'=>'200','object'=>$product]);

}
else{
return response()->json(['message'=>'Failed Query','status'=>'401']);
}

}

else{
  return response()->json(['error'=>$validator->errors(),'status'=>'401']);
}
}

public function relatedProduct($id){
$category=  Mod_category::whereId($id)->first();

if($category){
$category_name=$category->category_name;
$allproducts_by_category = DB::table('product_models')
->where('category', '=', $category_name)
->get();

if(count($allproducts_by_category)>0){

return response()->json(['message'=>'Successfull','status'=>'200','object'=>$allproducts_by_category]);
}
else{
return response()->json(['message'=>'No Prodcuts For This Category','status'=>'401']);
}

}
else{
return response()->json(['message'=>'Failed Query','status'=>'401']);
}
}




//remove cart item
public function removecartitem(Request $request){



$validator = Validator::make($request->all(), [
'user_id' => 'required|exists:users,id',
'cartitem_id' => 'required|exists:cart_items,id',
]);


if ($validator->passes()) {

$user_id=$request->input('user_id');
$cart_item_id=$request->input('cartitem_id');

//get cart by user id


$fetched_cart=DB::table('add_to_carts')
->where('user_id',$user_id)
->where('status','1')
->first();

if($fetched_cart!=null){
  
$cart_id=$fetched_cart->id;

//check if user has cart items 
$user_cart_items=DB::table('cart_items')
->where('cart_id',$cart_id)
->get();


if(count($user_cart_items)>0)                       {

$fetched_cart_item=DB::table('cart_items')
->where('id',$cart_item_id)
->where('cart_id',$cart_id)
->first();

if($fetched_cart_item)                {    
DB::table('cart_items')
->where('id',$cart_item_id)
->where('cart_id',$cart_id)
->delete();

//return all cart items list
$all_cart_items=DB::table('cart_items')
->where('cart_id',$cart_id)
->get();


if(count($all_cart_items)>0)    {

return response()->json(['message'=>'Successfull deletion / list of cart items this user and cart has ','status'=>'200','all_cart-items'=>$all_cart_items,'cart'=>$fetched_cart]);

                                }

else      {
return response()->json(['message'=>'Successfull deletion / No items in cart / after deleting  ','status'=>'200','all_cart-items'=>$all_cart_items,'cart'=>$fetched_cart]);
         }


                                      }
else              {
return response()->json(['message'=>'cart found / no cart item found  against product id', 'status','401','cart'=>$fetched_cart]);
                  }
//else for validation fails





                                                        }
  else{
    return response()->json(['message'=>'empty cart / no cart items against this cart ','status'=>'401','cart'=>$fetched_cart]);
  }

}

else{
  return response()->json(['message'=>'no cart against this user _id ','status'=>'401']); 
}

                                                      }

else{
return response()->json(['error'=>$validator->errors(),'status'=>'401']);
}

}





 public function getlistofcartitems($user_id){
$fetched_cart =DB::table('add_to_carts')
->where('user_id',$user_id)
->first();


if($fetched_cart){
$id=$fetched_cart->id;
$all_cart_items=DB::table('cart_items')
->where('cart_id',$id)
->where('status','1')
->get();



if(count($all_cart_items)>0){
return response()->json(['message'=>'list of cart items against user_id ','status'=>'200','cart_items_list'=>$all_cart_items,'cart'=>$fetched_cart]);
}


else{

return response()->json(['message'=>'cart exsist but its empty  ','status'=>'401','object'=>$fetched_cart]);

}
}



else{
return response()->json(['message'=>'no cart against & no cart items this user id ','status'=>'401']);
}




}





public function signin(Request $request){
$validator = Validator::make($request->all(), [


]);


if ($validator->fails()) {
return response()->json(['error'=>$validator->errors()], 401);
}
else{

$user = User::where('email' ,  $email=$request->input('email'))
//->where('password' ,bcrypt($password=$request->input('password')))
->where('password' ,$password=$request->input('password'))
->exists();



if($user){
$user_object=$user = User::where('email' ,  $email=$request->input('email'))
//->where('password' ,bcrypt($password=$request->input('password')))
->where('password' ,$password=$request->input('password'))
->first();
return response()->json(['message'=>'Successfull','status'=>'200','object'=>$user]);
}
else{
return response()->json(['message'=>'Invalid Credentials','status'=>'401']);
}
}


}


public function listoforders($id){

$orders = Mod_orders::where('user_id', $id)->get();
if(count($orders)>0){
return response()->json(['message'=>'Successfull','status'=>'200','object'=>$orders]);
}
else{
return response()->json(['message'=>'No Order Exsist','status'=>'401']);
}
}

public function token(){
echo csrf_token();
}


public function checkout (Request $request){


$validator = Validator::make($request->all(), [
'user_id' => 'required|exists:users,id',
'cart_id' => 'required|exists:add_to_carts,id',
]);


if ($validator->passes()) {

$user_id=$request->input('user_id');
$cart_id=$request->input('cart_id');
$payment_type=$request->input('payment_type');
$total_amount=$request->input('total_amount');
$delivery_address=$request->input('delivery_address');

$cart=add_to_cart::where('id',$cart_id)->first();

if($cart){
$cart_item=cart_items::where('cart_id',$cart_id)->get();
if(count($cart_item)>0){
$order=new Mod_orders;
$order->user_id=$user_id;
$order->cart_id=$cart_id;
$order->payment_type=$payment_type;
$order->total_amount=$total_amount;
$order->delivery_address=$delivery_address;
$order->status="Pending";
$order->save();
if($order){
$cart->status=0;
DB::table('add_to_carts')
->where('id',$cart_id)
->where('user_id',$cart->user_id)
->update(['status'=>'0']);

//putting all cart items status =0 
DB::table('cart_items')
->where('cart_id',$cart->id)
->update(['status'=>'0']);

return response()->json(['message'=>'Successfull /  order saved / cart is dead now / cart items also dead now  ','status'=>'200','cart'=>$cart,'cart_items'=>$cart_item,'order'=>$order]);
}
//failed to save order
else{
return response()->json(['message'=>'failed to update cart status to 0/dead / save order failed ','status'=>'401']);
}

}
//cart found but no cart items exsist
else{
return response()->json(['message'=>'Cart exsist but there are no products','status'=>'401']);
}
}
//cart not found 
else{
return response()->json(['message'=>'No cart exsist','status'=>'401']);
}

}


else{
  return response()->json(['error'=>$validator->errors(),'status'=>'401']);
}

}



}