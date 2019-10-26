<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\mymodels\add_to_cart;
use App\mymodels\cart_items;
use Illuminate\Support\Facades\Auth;
use App\Models\shopping_cart\Mod_Shoppingcart;
use App\Models\user_orders\Mod_orders;
use App\mymodels\reorder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordEmail;
use App\Models\products_categories\Mod_category;
use App\User;
use Validator;
use DB;
class Api_Controller2 extends Controller
{
    
public function reorder(Request $request){


$validator = Validator::make($request->all(), [
'order_id' => 'required|exists:orders,id',
]);

$order_id=$request->input('order_id');

if ($validator->passes()) {



    $order=DB::table('orders')
      ->where('id',$order_id)
      ->first();


  if($order){

    $cart_id=$order->cart_id;
    
    $found_cart=DB::where('add_to_carts')
    ->where('user_id',$user_id)
    ->first();


    if($found_cart){

    $found_cart_items=DB::table('cart_items')
    ->where('cart_id',$found_cart->id)
    ->get();

    if(count($found_cart_items)>0){
    
    		$update_cart_items_quantity=DB::table('cart_items')
    		->where('cart_id',$found_cart->id)
    		->update(['quantity'=>$found_cart_items->quantity+$found_cart_items->quantity]);
    		 $reorders=new reorder;
     		 $reorders->order_id=$order_id;
     		 $reorders->user_id=$order->user_id;
      		 $reorders->cart_id=$order->cart_id;
             $reorders->total_amount=$order->total_amount;
             $reorders->payment_type=$order->payment_type;
             $reorders->delivery_address=$order->delivery_address;
             $reorders->save();

  if($reorders){

 return response()->json(['message'=>'success reorder generated ','status'=>'200','reorder'=>$reorders,'order'=>$order]);
     		 }

  else 		{
     return response()->json(['message'=>'failed / reorder failed to save ','status'=>'401']);
    		  }


    }
    else{
    	return response()->json(['message'=>'no cart items in this cart found ','status'=>'401']);
    }
}
else{
return response()->json(['message'=>'no cart found  ','status'=>'401']);
}
}
else{
return response()->json(['message'=>'no orders found   ','status'=>'401']);

}
  }

else{
  return response()->json(['error'=>$validator->errors(),'status'=>'401']);
} 


}//end of function 


public function addtocart(Request $request){


$validator = Validator::make($request->all(), [
'user_id' => 'required|exists:users,id',
'product_id' => 'required|exists:product_models,id',
'quantity' => 'required|max:100|min:1',          
]);

if($validator->passes()){


$user_id=$request->user_id;
$product_id=$request->product_id;
$quantity=$request->quantity;



$user_cart=DB::table('add_to_carts')
->where('user_id',$user_id)
->first();



if( $user_cart!=null && $user_cart->status=='1'){

$user_cart_items=DB::table('cart_items')
->where('cart_id',$user_cart->id)
->where('product_id',$product_id)
->first();



if($user_cart_items!=null){
$user_cart_items=DB::table('cart_items')
->where('cart_id',$user_cart->id)
->where('product_id',$product_id)
->update(['quantity'=>$user_cart_items->quantity=$user_cart_items->quantity+$quantity]);



$all_cart_items=DB::table('cart_items')
->where('cart_id',$user_cart->id)
->get();

return response()->json(['message'=>'case : user has cart with status / active product quantity increased ','status'=>'200','cart'=>$user_cart,'cart_items'=>$all_cart_items]);

}
else{

$product=DB::table('product_models')
->where('id',$product_id)
->first();

$new_cart_item=new cart_items;
$new_cart_item->cart_id=$user_cart->id;
$new_cart_item->quantity=$quantity;
$new_cart_item->product_id=$product_id;
$new_cart_item->name=$product->name;
$new_cart_item->price=$product->price;
$new_cart_item->detail=$product->detail;
$new_cart_item->featured_image=$product->featured_image;
$new_cart_item->status='1';
$new_cart_item->save();

if($new_cart_item){

$all_cart_items=DB::table('cart_items')
->where('cart_id',$user_cart->id)
->get();
return response()->json(['message'=>'case:user has cart with status active / new cart item added  ',
'cart'=>$user_cart,
'cart_items'=>$all_cart_items
,'status'=>'200']);
}
else{
return response()->json(['message'=>'case : user has cart with status active / new cart item creation failed ',
'status'=>'401']);
}






}

}

else if($user_cart!=null && $user_cart->status=='0'){
$new_cart=new add_to_cart;
$new_cart->user_id=$user_id;
$new_cart->status='1';
$new_cart->vat=20;
$new_cart->delivery_address="Weblinerz Harley Street london";
$new_cart->delivery_fee=0;
$new_cart->vat=0;
$new_cart->save();



$product=DB::table('product_models')
->where('id',$product_id)
->first();


$new_cart_item=new cart_items;
$new_cart_item->cart_id=$new_cart->id;
$new_cart_item->quantity=$quantity;
$new_cart_item->product_id=$product_id;
$new_cart_item->name=$product->name;
$new_cart_item->price=$product->price;
$new_cart_item->detail=$product->detail;
$new_cart_item->featured_image=$product->featured_image;
$new_cart_item->status='1';
$new_cart_item->save();
if($new_cart_item){
return response()->json(['message'=>'case:user has old cart with status dead / new cart created','cart'=>$new_cart,'cart_items'=>$new_cart_item,'status'=>'200']);
}
else{

return response()->json(['message'=>'case:user has old cart with status dead / failed ','status'=>'401']);

}
}


else{
$new_cart=new add_to_cart;
$new_cart->user_id=$user_id;
$new_cart->status='1';
$new_cart->vat=20;
$new_cart->delivery_address="Weblinerz Harley Street london";
$new_cart->delivery_fee=0;
$new_cart->vat='0';
$new_cart->save();

$product=DB::table('product_models')
->where('id',$product_id)
->first();



$new_cart_item=new cart_items;
$new_cart_item->cart_id=$new_cart->id;
$new_cart_item->quantity=$quantity;
$new_cart_item->product_id=$product_id;
$new_cart_item->name=$product->name;
$new_cart_item->price=$product->price;
$new_cart_item->detail=$product->detail;
$new_cart_item->featured_image=$product->featured_image;
$new_cart_item->status='1';
$new_cart_item->save();
if($new_cart_item){
return response()->json(['message'=>'case:user does not has any previous cart ','cart'=>$new_cart,'cart_items'=>$new_cart_item,'status'=>'200']);
}
else{
return response()->json(['message'=>'case :user does not has any previous cart / failed  ','status'=>'401']);
}

}

}

else{



}

}

//update prfile 

public function updateProfile(Request $request){

$validator = Validator::make($request->all(), [
'id'=>'exists:users,id'
]);


$fname=$request->input('fname');
$lname=$request->input('lname');
$email=$request->input('email');
$contact_no=$request->input('contact_no');
$post_code=$request->input('post_code');
$address=$request->input('address');




if ($validator->passes()) {
$checkuser=DB::table('users')
->where('id',$request->input('id'))
->first();

if($checkuser){
  $user=User::findOrfail($request->input('id'));
 $user->fname=$request->input('fname');
 $user->lname=$request->input('lname');
 $user->email=$request->input('email');
 $user->contact_no=$request->input('contact_no');
 $user->post_code=$request->input('post_code');
 $user->address=$request->input('address');
 $user->update();
 $user=DB::table('users')
  ->where('id',$request->id)
  ->first();
if($user){
return response()->json(['message'=>'Profile Updated Successfully','status'=>'200','updated_user_object'=>$user]);
}
else{
return response()->json(['message'=>'failed to update profile ','status'=>'401']);

}


}
else{


return response()->json(['message'=>'Failed to find user ','status'=>'401']);


}
}
else{
  return response()->json(['error'=>$validator->errors(),'status'=>'401']);
}
}





//update profile end here 






public function orderdetails($order_id){

$order=DB::table('orders')
->where('id',$order_id)
->first();


if($order){
$cart_id=$order->cart_id;
$user_cart=DB::table('add_to_carts')
->where('id',$cart_id)
->first();

if($user_cart){

$user_cart_items=DB::table('cart_items')
->where('cart_id',$cart_id)
->get();



if(count($user_cart_items)>0){

  return response()->json(['message'=>'success','status'=>'200','order'=>$order,'user_cart'=>$user_cart,'user_cart_items'=>$user_cart_items]);
}
else{
return response()->json(['message'=>'cart found but no cart items this order  ','status'=>'200','order'=>$order,'user_cart'=>$user_cart]);

}





}
else
return response()->json(['message'=>'failed to find any cart against user order ','status'=>'401']);

}
else{
return response()->json(['message'=>'failed ....found no order ','status'=>'401']);
}




}

public function getlistoforders($user_id){



$all_orders =DB::table('orders')
->where('user_id',$user_id)
->get();
if(count($all_orders)>0){
return response()->json(['message'=>'list of all orders against user_id ','status'=>'200','order_list'=>$all_orders]);
}
else{
return response()->json(['message'=>'no orders_against this user_id ','status'=>'401']);
}

}


public function forgotpassword(Request $request){

$get_email=$request->input('email');
$user_id=$request->input('user_id');

$get_user=DB::table('users')
->where('id',$user_id)
->first();
if($get_user){
$genrated_password = Hash::make(str_random(8));

$get_user=DB::table('users')
->where('id',$user_id)
->update(['password'=>$genrated_password]);

if($get_user){
$get_user=DB::table('users')
->where('id',$user_id)
->first();

    $name =$get_user->fname;
    $password=$get_user->password;
    $email=$get_email;
   Mail::to($email)->send(new ForgotPasswordEmail($name,$password));
   
   return response()->json(['message'=>'password changed email sent','user'=>$get_user,'status'=>'200']);
}  
else{
return response()->json(['message'=>'failed']);
}
}

else{
  return response()->json(['message'=>'failed']);
}






// end of forgot password 


}

public function allcategories(){

$all=Mod_category::all();
if($all){
  return response()->json(['message'=>'success ','status'=>'200','categories_list'=>$all]);
}
else{
  return response()->json(['message'=>'operation failed ','status'=>'401']);
}
}

public function get_subcategory($id){
$category=DB::table('categories')
->where('id',$id)
->first();

$all=$category=DB::table('subcategories')
->where('parent_category',$id)
->get();


if($all ){
  return response()->json(['message'=>'success ','status'=>'200','parent _category'=>$category,'child_categories'=>$all]);
}
else{
  return response()->json(['message'=>'operation failed ','status'=>'401']);
}
}

}
