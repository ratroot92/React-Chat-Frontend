<?php

namespace App\Http\Controllers\Admin_Panne\companies;

use App\Models\admin\Companies\Mod_company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class companyController extends Controller
{
     public function Company_index()
     {
         $company            = Mod_company::all();
         return view('admin.pages.companies.company',[
             'company' => $company
         ]);
     }
     public function add_company()
     {
         return view('admin.pages.companies.add');
     }
     public function save(Request $request)
     {
         $company              = new Mod_company();
         $input                = $request->all();
         $value                = $input['page'];
         $company->name        = $input['company_name'];
         $company->type        = $input['company_type'];
         $company->address     = $input['company_address'];
         $company->email       = $input['company_email'];
         $company->contact_no  = $input['company_no'];
         if($request->hasFile('file')){

             $filename=$request->file->getClientOriginalName();
             $request->file->move(public_path('images'),$filename);
             $company->image  = $filename;
         }
         $company->save();
         if ($value=="admin")
         {
             return redirect('/company_index');
         }
         else
         {
             return redirect('/index')->with('company_msg','Your Company is added successfully');
         }

     }
    // edit company
    public function edit_company($id)
    {
        $company            = Mod_company::findorfail($id);
        return view('admin.pages.companies.edit',
            [
                'company'       => $company
            ]);
    }

    // update company
    public function update_company(Request $request,$id)
    {
        $company              = Mod_company::findorfail($id);
        $input                = $request->all();
        $company->name        = $input['company_name'];
        $company->type        = $input['company_type'];
        $company->address     = $input['company_address'];
        $company->email       = $input['company_email'];
        $company->contact_no  = $input['company_no'];
        if($request->hasFile('file')){
            $filename=$request->file->getClientOriginalName();
            $request->file->move(public_path('images'),$filename);
        }
        $company->image       = $filename;
        $company->save();
        return redirect('/company_index');
    }

    public function company_record($id)
    {
        $comapny        = Mod_company::findorfail($id);
        return response()->json($comapny);
    }
    public function delete_company($id)
    {
        $comapny        = Mod_company::findorfail($id);
        $comapny->delete();
        return redirect('/company_index');
    }
    public function register_company()
    {
        return view('pages.company_registration');
    }
}
