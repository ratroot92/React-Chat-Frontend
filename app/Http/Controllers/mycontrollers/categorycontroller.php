<?php

namespace App\Http\Controllers\mycontrollers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\mymodels\category_model;
use Validator;
use Response;
class categorycontroller extends Controller
{
    public function index(){
    	$lastcategory= category_model::orderBy('created_at','desc')->first();
       if ($lastcategory!=null)
       {
           $lastcategory_id              = $lastcategory->id+1;
       }
       else
       {
           $lastcategory_id              = 1;
       }

       //get alll categories 
       $all_categories=category_model::all();
    return view('admin/pages/n_categories/categories',compact('lastcategory_id','all_categories'));
    }


    public function index_with_category_id(){

    }

    public function insert(Request $request){
    	 $validator = Validator::make($request->all(), [
    		'cat_id'=>'required|unique:category_models,id',
    		'cat_name'=>'required|unique:category_models,name',
    		'cat_desc'=>'required|min:30|max:200',
    		'cat_status'=>'required|integer',

    	]);
        if ($validator->passes()) {
$new_category= new category_model;
$new_category->id=$request->input('cat_id');
$new_category->name=$request->input('cat_name');
$new_category->description=$request->input('cat_desc');
$new_category->status=$request->input('cat_status');
$new_category->save();
if($new_category){
return Response::json(['success'=>'Category Successfully Saved ']);
}
else{
return Response::json(['error'=>'Request Failed' ]);
}


    }//end of insert 


	 else{
            return response()->json(['error' => $validator->errors()->all()]);
        }
}


public function getcatagoryinfo($id){
	$getcategory=category_model::findOrFail($id)->first();
	if($getcategory){


	$sendcategorytomodal=new category_model;
	$name=$getcategory->name;
	$description=$getcategory->description;
	$status=$getcategory->status;
	
	return Response::json(['id'=>$id,'name'=>$name,'description'=>$description,'status'=>$status]);
}
else{
return Response::json(['error'=>'Unable to find any Category against edit id ']);
}



}//end of getcategory info message 



public function edit(Request $request){

	$Validator = Validator::make($request->all(), [
			'cateditid'=>'required',
    		'cateditname'=>'required',
    		'cateditdesc'=>'required|min:30|max:200',
    		'cateditstatus'=>'required|integer',
		

	]);

	if($Validator->passes()){
			$updateable_category=category_model::findOrFail($request->input('cateditid'));
				
			if($updateable_category){
			$updateable_category->name=$request->input('cateditname');
			$updateable_category->description=$request->input('cateditdesc');
			$updateable_category->status=$request->input('cateditstatus');
			$updateable_category->save();

 			return Response::json(['success'=>'Category has been updated']);
		}

		else{

return Response::json(['error'=>'Category not found ! Database error']);
		}



	}

else{
 return Response::json(['error' => $Validator->errors()->all()]);
}
}



public function rendercategorytable(){
  $all_categories=category_model::all();
return view('admin/pages/n_categories/table',compact('all_categories'));
}



public function delete($id){
$flag=category_model::findOrFail($id)->delete();
if($flag){
return Response::json(['success'=>'Category Deleted Successfully !']);
}
else{
return Response::json(['error'=>'Database Error !  ']);
}
}



}//end of controller 
