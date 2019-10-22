<?php

namespace App\Http\Controllers\Entr_Vendr_auth;
use App\entr_vendr;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
class RegisterController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:entr_vendrs',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $input                               = $request->all();
        $entr_vendr                          = new entr_vendr();
        $entr_vendr->name                    = $input['name'];
        $entr_vendr->email                   = $input['email'];
        $entr_vendr->address                 = $input['address'];
        $entr_vendr->contact_no              = $input['contact_no'];
        $entr_vendr->password                = Hash::make($input['password']);
        $entr_vendr->type                    = $input['type'];
        $entr_vendr->status                  =1 ;
        $entr_vendr->save();
        return response()->json('You are registered successfully');
    }
}
