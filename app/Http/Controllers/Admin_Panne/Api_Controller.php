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
use  App\Models\user_orders\Mod_orders;
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
public function updateProfile(Request $request){
    


  //if($user = User::findOrFail($request->id)->first()){
    if($user = User::whereId($request->id)->first()){
            $user->fname=$request->input('fname');
            $user->lname=$request->input('lname');
            $user->email=$request->input('email');
            $user->contact_no=$request->input('contact_no');
            $user->post_code=$request->input('post_code');
            $user->address=$request->input('address');
            $user->password=$request->input('password');
            $user->save();
            if($user){
                return response()->json(['message'=>'Profile Updated Successfully','status'=>'200','object'=>$user]);
            }
            else{
                 return response()->json(['message'=>'some error ','status'=>'401']); 
 
            }
 

}
else{


   return response()->json(['message'=>'Failed to find user ','status'=>'401']); 

        
   }
}
      
           



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
     $product=product_model::whereId($id)->first();


       if($product){
return response()->json(['message'=>'Successfull','status'=>'200','object'=>$product]);
     
          }
          else{
return response()->json(['message'=>'Failed Query','status'=>'401']);
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

public function addToCart(Request $request){
//global request variables 

$user_id=$request->input('user_id');
$product_id=$request->input('product_id');
$quantity=$request->input('quantity');
$id=0;


//check if cart exsist against this user_id with status 1

$feteched_cart=DB::table('add_to_carts')
                   ->where('user_id',$user_id)
                   ->where('status',1)
                   ->first();

if($feteched_cart){
//cart qith user-id already exsist
//fetch that cart and get its id 
  $id=$feteched_cart->id;

  //check if product id already exist in cart item
   $fetched_cart_item=DB::table('cart_items')
                      ->where('cart_id',$id)
                      ->where('product_id',$product_id)
                      ->first();
                     

//if cart item already exsist
if($fetched_cart_item){
     DB::table('cart_items')
                      ->where('cart_id',$id)
                      ->where('product_id',$product_id)
                      ->update(['quantity'=>$fetched_cart_item->quantity+$quantity]);

      $a=  DB::table('cart_items')
       ->where('cart_id',$id)
        ->where('product_id',$product_id)
        ->first();

 return response()->json(['message'=>'cart exsist product exsist quantity added ','object'=>$feteched_cart,'object2'=>$a,'status'=>'200']);

     
                      }
    
else{

      $product1=  DB::table('product_models')
       ->where('id',$product_id)
        ->first();

        
$newcartitem=new cart_items;
$newcartitem->cart_id=$id;
$newcartitem->product_id=$product_id;
$newcartitem->status=1;
$newcartitem->quantity=$quantity;
$newcartitem->name=$product1->name;
$newcartitem->price=$product1->price;
$newcartitem->detail=$product1->detail;
$newcartitem->featured_image=$product1->featured_image;
$newcartitem->save();
if($newcartitem){

return response()->json(['message'=>'product already exsist but the cart item doesnot ','object'=>$feteched_cart,'object2'=>$newcartitem,'status'=>'200']); 

}

else{

return response()->json(['message'=>'failure in cart exsist but product is new   ','status'=>'401']);

}

    }







}
else{
//cart doesnot exsisit make a new cart 
$newcart= new add_to_cart;
$newcart->user_id=$user_id;
$newcart->status=1;
$newcart->save();

      $product=  DB::table('product_models')
       ->where('id',$product_id)
        ->first();
//now make a new cart item 
$newcartitem=new cart_items;
$newcartitem->cart_id=$newcart->id;
$newcartitem->product_id=$request->input('product_id');
$newcartitem->status=1;
$newcartitem->quantity=$quantity;
$newcartitem->name=$product->name;
$newcartitem->price=$product->price;
$newcartitem->detail=$product->detail;
$newcartitem->featured_image=$product->featured_image;
$newcartitem->save();
if($newcartitem)
{

return response()->json(['message'=>'new cart and cart item created  ','object'=>$newcart,'object2'=>$newcartitem]);

}
else{

return response()->json(['message'=>'failusre in cart doesnot exsisit make a new cart  ','status'=>'401']);
}

}
 


}


//remove cart item 
    public function removecartitem(Request $request){
     
      $user_id=$request->input('user_id');
      $cart_item_id=$request->input('cartitem_id');

      //get cart by user id 
      $fetched_cart=DB::table('add_to_carts')
                      ->where('user_id',$user_id)
                      ->first();

        if($fetched_cart){
           $cart_id=$fetched_cart->id;

      $fetched_cart_item=DB::table('cart_items')
                      ->where('id',$cart_item_id)
                      ->delete();

    

      //return all cart items list 
      $all_cart_items=DB::table('cart_items')
                      ->where('cart_id',$cart_id)
                      ->get();
        if(count($all_cart_items)>0){
return response()->json(['message'=>'list of cart items this user and cart has ','status'=>'200','cart-items'=>$all_cart_items,'cart'=>$fetched_cart]);

        }

        else
        {

return response()->json(['message'=>'this user has no cart _items left ','status'=>'401']);
        
        }

        }

     else
     {

      return response()->json(['message'=>'no cart against this id  ','status'=>'401']);
     
     }


    }



public function getlistofcartitems($user_id){

 $fetched_cart=DB::table('add_to_carts')
                      ->where('user_id',$user_id)
                      ->first();


        if($fetched_cart){
           $id=$fetched_cart->id;
          $all_cart_items=DB::table('cart_items')
                      ->where('cart_id',$id)
                      ->get();



              if(count($all_cart_items)>0){
return response()->json(['message'=>'list of cart items against user_id ','status'=>'200','cart-items-list'=>$all_cart_items,'cart'=>$fetched_cart]);
}

              
              else{

   return response()->json(['message'=>'cart exsist but its empty  ','status'=>'401','object'=>$fetched_cart]);
  
              }
            }



else{
    return response()->json(['message'=>'no cart against & no cart items this user id ','status'=>'401']);
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




}

