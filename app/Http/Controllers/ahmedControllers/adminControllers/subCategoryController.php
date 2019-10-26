<?php

namespace App\Http\Controllers\ahmedControllers\adminControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\mymodels\Mod_Subcategory;
use App\Models\products_categories\Mod_category;
use Validator;
use DB;
class subCategoryController extends Controller
{
    public function view(){

    	$category=Mod_category::all();
    	
    	return view ('admin.pages/subcategory/add_subcategory',compact('category'));
    }


public function index(){
 $pr_category      =Mod_category::all();
 $subcategories    =Mod_Subcategory::all();

if(count($pr_category)>0 && count($subcategories)>0){
  return view('admin/pages/subcategory/edit_subcategory',[
                'pr_category'=>$pr_category,
                'subcategories'=>$subcategories

              ]);
}
else{
  $pr_category->category_name="N0 Category";
  return view('admin/pages/subcategory/edit_subcategory',[
                'pr_category'=>$pr_category,
                'subcategories'=>$subcategories,
              ]);
}

 

}

    public function addSubCategories(Request $request){

$validator = Validator::make($request->all(), [

		'name'       =>'required|min:3|max:30',
		'category'   =>'required|exists:categories,category_name',
		'description'=>'required|min:10',
		'status'     =>'required|integer',
		'file'       =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                                            ]);



  if ($validator->fails()) {
    	return redirect('admin/sub_category')
               ->withErrors($validator)
               ->withInput();

           

            }

else{

		

	if($request->file('file')){
		$image           =$request->file('file');
		$name            = time().'.'.$image->getClientOriginalExtension();
    $destinationPath = public_path('/AdminImages/Sub_Categories_Images/');
    $databse_path ='http://chameleon.mediabloo.com/public//AdminImages/Sub_Categories_Images/'.$name;
    $image->move($destinationPath, $name);



$parent_category                   =Mod_category::where('category_name',$request->input('category'))->                                first();
 $new_sub_category                 =new Mod_Subcategory;
 $new_sub_category->name           =$request->input('name');
 $new_sub_category->description    =$request->input('description');
 $new_sub_category->image          =$databse_path;
 $new_sub_category->status         =$request->input('status');
 $new_sub_category->parent_category=$parent_category->id;
 $new_sub_category->save();

if($new_sub_category){

 return redirect('admin/sub_category')->with('success','Sub Category added successfully ');
}


else{
	return redirect()->route('admin/sub_category')->with('error','Operation failed !   ');
}

}




//has file else 
else{
	return redirect()->route('admin/sub_category')->with('error','Operation failed  ');
}



}
    }

public function get_sub_cat_for_edit($id){

$subcategory= DB::table('subcategories')
->where('id',$id)
->first();

 $id=$subcategory->parent_category;

$category                  =DB::table('categories')
                           ->where('id',$id)
                           ->first();
$pr_category               =$subcategory->parent_category=$category->category_name;


return response()->json($subcategory);


}



public function edit_subcategory(Request $request){


$validator = Validator::make($request->all(), [

        'name'       =>'required|min:3|max:30',
        'pr_category'   =>'required',
        'description'=>'required|min:10',
        'status'     =>'required|integer',
       
]

);



  if ($validator->fails()) {
        return  redirect('/admin/subcategories')
                ->withErrors($validator)
                ->withInput();

           

            }

else{

        
$id                       =$request->input('id');
$name                     =$request->input('name');
$description              =$request->input('description');
$status                   =$request->input('status');
$pr_category_name         =$request->input('pr_category');



$category                =Mod_category::where('category_name',$pr_category_name)->first();

$updated_record          =DB::table('subcategories')->where('id',$id)
                            ->update([
                            'name'           =>$name,
                            'description'    =>$description,
                            'status'         =>$status,
                            'parent_category'=>$category->id,
                            ]);  


 if($updated_record){
 
return redirect('/admin/subcategories')->with('success','Sub Category added successfully ');
 
 }

    
else{

 return redirect('/admin/subcategories')->with('success','No changes !   ');

    }











}




}


  
  public function del_sub_cat($id){
  
  
  $deleted_category             =Mod_Subcategory::whereId($id)->delete();
  if($deleted_category)             {

  return redirect('/admin/subcategories')->with('success','Subcategory deleted successfully');

                                    }

  else                              {
  return redirect('/admin/subcategories')->with('success','Operation failed ');   
                                    }
  
  }
  





}