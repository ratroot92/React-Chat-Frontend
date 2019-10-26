<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ------------------------------- Start of Admin Routes -------------------------------

Route::get('admin/login','Admin_auth\LoginController@showLoginForm');
Route::post('/admin/login','Admin_auth\LoginController@login');
Route::post('/admin/logout','Admin_auth\LoginController@logout')->name('logout');

// Dashboard
Route::group(['middleware' =>['admin'],'prefix' => 'admin'],function (){
    Route::get('/dashboard','Admin_Panne\main\admin_main_Controller@main_index');
  // Stores


    //ahmad route
    Route::get('/add_store','Admin_Panne\main\admin_main_Controller@add_store');
    Route::post('/addCategories','Admin_Panne\main\admin_main_Controller@addcategory');
    Route::post('/edit_cat_modal','Admin_Panne\main\admin_main_Controller@edit_cat');
    Route::get('/del_cat/{id}','Admin_Panne\main\admin_main_Controller@del_cat');
    Route::get('/get_cat_for_edit/{id}','Admin_Panne\main\admin_main_Controller@get_cat_for_edit');
     
Route::get('/sub_category','ahmedControllers\adminControllers\subCategoryController@view');
Route::get('/subcategories','ahmedControllers\adminControllers\subCategoryController@index');
Route::get('/get_sub_cat_for_edit/{id}','ahmedControllers\adminControllers\subCategoryController@get_sub_cat_for_edit');
Route::post('/edit_subcategory','ahmedControllers\adminControllers\subCategoryController@edit_subcategory');
Route::get('/del_sub_cat/{id}','ahmedControllers\adminControllers\subCategoryController@del_sub_cat');  
     
Route::post('/addSubCategories','ahmedControllers\adminControllers\subCategoryController@addSubCategories');






    Route::get('/stores','Admin_Panne\main\admin_main_Controller@store_index');
  
    Route::get('/drivers','Admin_Panne\main\admin_main_Controller@drivers');
    Route::get('/add_driver','Admin_Panne\main\admin_main_Controller@add_driver');
    Route::get('/driver_schedule','Admin_Panne\main\admin_main_Controller@driver_schedule');
    //Products

    Route::get('/products','Admin_Panne\main\admin_main_Controller@products');
    Route::get('/add_product','Admin_Panne\main\admin_main_Controller@add_product');
    Route::post('/insert_product ','Admin_Panne\main\admin_main_Controller@insert_product');
    Route::post('/edit_product ','Admin_Panne\main\admin_main_Controller@edit_product');
    Route::get('/get_pro_for_edit/{id}','Admin_Panne\main\admin_main_Controller@get_pro_for_edit');
    Route::get('/del_pro/{id}','Admin_Panne\main\admin_main_Controller@del_pro');

    //Orders
    Route::get('/orders','Admin_Panne\main\admin_main_Controller@orders');
    //payments
    Route::get('/payments','Admin_Panne\main\admin_main_Controller@payments');
    //sales
    Route::get('/sales','Admin_Panne\main\admin_main_Controller@sales');
    //settings
    Route::get('/settings','Admin_Panne\main\admin_main_Controller@settings');
// recent orders
    Route::get('/view_order/{id}','Admin_Panne\main\admin_main_Controller@view_order');
    Route::get('/deposit_amount/{id}','Admin_Panne\main\admin_main_Controller@deposit_amount');
    Route::post('/update_amount/{id}','Admin_Panne\main\admin_main_Controller@update_amount');






// messages
    Route::get('/messages','Admin_Panne\messsages\messagesController@index');
    Route::get('/view_message/{id}','Admin_Panne\messsages\messagesController@view');
});

// -------------------------------- End of Admin Routes ------------------------------------

//---------------------------------Start of Entreprneur & Vendor Routes -----------------------
Route::get('/entrpreneur/{value}','admin\joined_us\joinedController@entrpreneur_index');
Route::post('/entr_login','Entr_Vendr_auth\LoginController@login');
Route::group(['middleware' => ['entr_vendor'], 'prefix' => 'entr_vendor'],function (){
    Route::get('/dashboard','admin\joined_us\joinedController@entr_dashboard');
    Route::get('/dashboard1','admin\joined_us\joinedController@vendr_dashboard');
    Route::get('/entr_product','admin\joined_us\joinedController@add_product');
    Route::post('/save_entr_product','admin\joined_us\joinedController@save_product');
    Route::get('/vendr_apply','admin\joined_us\joinedController@apply');
    Route::get('/logout','Entr_Vendr_auth\LoginController@logout');
    Route::post('/save_apply','admin\joined_us\joinedController@save_apply');
});

Route::post('/vendor_register','Entr_Vendr_auth\RegisterController@register');
// Join rown.pk as

//---------------------------------- End of Entreprneur & Vendor Routes---------------------

//  main index page and Navigation bar controllers
Route::get('/','admin\main\mainController@index');
Route::get('index','admin\main\mainController@index');
Route::get('/signup','admin\main\mainController@Sign_Up');
Route::get('/quickview/{productid}','admin\main\mainController@Quick_View');
//Route::get('/dashboard','admin\main\mainController@user_dashboard');

Route::get('/about_us','admin\main\mainController@about_us');
Route::get('/contact_us','admin\main\mainController@contact_us');
Route::get('/shopping_grid/{category}','admin\main\mainController@Old_Shopping_Grid');


// user messages
Route::post('/user_messages','admin\user_messages\user_messageController@save_message');

// user Account Settings
Route::get('/acct_setting/{id}','admin\user_accounts\UserAccountController@Account_setting');
Route::post('/update_userinfo/{id}','admin\user_accounts\UserAccountController@user_info');
Route::post('/update_userpic/{id}','admin\user_accounts\UserAccountController@user_pic');
Route::post('/change_pass/{id}','admin\user_accounts\UserAccountController@change_password');

// user Privacy settings
Route::get('/privacy_index','admin\user_privacy\PrivacyController@privacy_index');

// Replace items
Route::get('/replace_index','admin\replace_items\ReplaceItemsController@index');
Route::post('/replace_save','admin\replace_items\ReplaceItemsController@save');

// company_registration
Route::get('/company_registration','Admin_Panne\companies\companyController@register_company');
// Shopping cart
Route::get('/view_cart','admin\shopping_cart\ShoppingCartController@Cart_view');
Route::get('/add_items_cart/{product_id}','admin\shopping_cart\ShoppingCartController@add_to_cart');
Route::get('/delete_items/{id}','admin\shopping_cart\ShoppingCartController@delete_item');
Route::get('/empty_cart','admin\shopping_cart\ShoppingCartController@empty_cart');

// Promo code
Route::post('/add_code/{amount}','admin\promo_codes\promocodeController@Add_Promo');

// Delivery
Route::post('/delivery','admin\delivery_info\deliveryController@save_delivery_info');

// checkout and ordered products
Route::get('/invoice/{discount}','admin\ordered_items\orderController@invoice');
Route::post('/orders','admin\ordered_items\orderController@post_orders');
Route::get('/my_orders','admin\ordered_items\orderController@my_orders');
Route::get('/view_my_order/{order_id}','admin\ordered_items\orderController@view_order');

// user payments
Route::get('/index_payment/{id}','admin\user_payments\paymentsController@payment_index');
Route::post('/savepayment','admin\user_payments\paymentsController@save_payement');
Route::get('/editpayment/{paymentid}','admin\user_payments\paymentsController@edit_payment');
Route::post('/update_payment/{paymentid}','admin\user_payments\paymentsController@update_payment');
Route::get('/deletepayment/{payment_id}/{user_id}','admin\user_payments\paymentsController@delete_payement');

// Whishlist products
Route::get('/add_to_whishlist/{product_id}','admin\whishlist\whishlistController@add_items');

// News Letters/ User subscriptions
Route::post('/news_letters','admin\News_letters\news_lettersController@save_news_letter');

Auth::routes();



Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//ahmad routes
//ahmed
   
Route::group(['prefix' => 'admin'], function () {
  
});
// //my routes for categories 
// Route::get('/categories','mycontrollers\categorycontroller@index')->name('categories');
// Route::get('/rendercategorytable','mycontrollers\categorycontroller@rendercategorytable')->name('rendercategorytable');
// //insert category data 
// Route::post('insertcategory','mycontrollers\categorycontroller@insert')->name('insertcategory');
// Route::get('getcategioryinfo/{id}','mycontrollers\categorycontroller@getcatagoryinfo')->name('getcategioryinfo');
// Route::post('/editcategory','mycontrollers\categorycontroller@edit')->name('editcategory');
// Route::get('/deletecategory/{id}','mycontrollers\categorycontroller@delete')->name('deletecategory');

// //my routes for products
// Route::get('/products','mycontrollers\productcontroller@index')->name('prdoucts');
// Route::post('/insertproduct','mycontrollers\productcontroller@insert')->name('insertproduct');
// Route::get('/renderproducttable','mycontrollers\productcontroller@renderproducttable')->name('renderproducttable');




Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
});