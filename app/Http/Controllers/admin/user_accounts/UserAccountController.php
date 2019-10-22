<?php

namespace App\Http\Controllers\admin\user_accounts;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class UserAccountController extends Controller
{
    public function Account_setting($id)
    {
       $user   = User::where('id','=',$id)->first();
        return view('pages.account_setting',
              ['user' => $user]
            );
    }
    public function user_info($id,Request $request)
    {

        $user               = User::findorfail($id);
        $input              = $request->all();
        $user->name         = $input['user_name'];
        $user->email        = $input['user_email'];
        $user->city         = $input['user_city'];
        $user->contact_no   = $input['user_contact'];
        $user->address      = $input['user_address'];
        $user->save();
        return response()->json("updated successfully");
    }
    public function user_pic($id,Request $request)
    {
        $user               = User::findorfail($id);
        if($request->hasFile('file')){

        $filename=$request->file->getClientOriginalName();
        $request->file->move(public_path('images'),$filename);
    }
        $user->image = $filename;
        $user->save();
       return redirect('/index');
    }
    public function change_password($id,Request $request)
    {
        return $this->validate($request,[
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user               = User::findorfail($id);
        $input              = $request->all();
        $user->password     = bcrypt($input['password']);
        $user->save();
        return response()->json('password has been changed');
    }
}
