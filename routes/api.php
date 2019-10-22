<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// ahmed api sdone
//remote mysql on xampp or mysqlworkbench >???
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('/signin', 'api\Api_Controller@signin');
Route::post('/signup', 'api\Api_Controller@signup');
Route::post('/updateprofile', 'api\Api_Controller2@updateProfile');
Route::get('/listspecialproducts','api\Api_Controller@listSpecialProducts');
Route::get('/listproductsbycategory/{id}','api\Api_Controller@listProductsByCategory');
Route::get('/productdetail/{id}','api\Api_Controller@productDetail');
Route::get('/relatedproduct/{id}','api\Api_Controller@relatedProduct');
Route::post('/addtocart','api\Api_Controller2@addToCart');
Route::get('/listoforders/{id}', 'api\Api_Controller@listoforders');
Route::post('/removecartitem', 'api\Api_Controller@removecartitem');
Route::get('/getlistofcartitems/{user_id}', 'api\Api_Controller@getlistofcartitems');
Route::get('/getlistoforders/{id}', 'api\Api_Controller2@getlistoforders');
Route::post('/checkout', 'api\Api_Controller@checkout');
Route::post('/reorder', 'api\Api_Controller2@reorder');
Route::get('orderdetails/{id}','api\Api_Controller2@orderdetails');
Route::post('forgot','api\Api_Controller2@orderdetails');
//saad api start here 
Route::get('/allorders', 'api\Api_Controller2@allOrders');

Route::get('/test', 'api\Api_Controller2@test');

Route::group(['middleware' => 'auth:api'], function(){

    Route::post('details', 'UserController@details');
});


?>