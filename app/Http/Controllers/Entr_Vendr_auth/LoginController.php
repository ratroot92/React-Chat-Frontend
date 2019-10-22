<?php

namespace App\Http\Controllers\Entr_Vendr_auth;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest:entr_vendr')->except('logout');
    }
    protected function username() {
        return 'email';
    }
    public function login(Request $request)
    {
        $email                 = $request->email;
        $password              = $request->password;
        if (Auth::guard('entr_vendr')->attempt(['email' => $email,'password' => $password]))
        {
            $type              = Auth::guard('entr_vendr')->user()->type;
            return response()->json($type);
        }
        else
        {
            return response()->json('Email or password is not correct');
        }
    }
    protected function guard()
    {
        return Auth::guard('entr_vendr');
    }
        public function logout(Request $request)
    {
        $type           = Auth::guard('entr_vendr')->user()->type;
        $this->guard()->logout();

        $request->session()->invalidate();

        return response()->json($type);
        //return $this->loggedOut($request) ?: redirect('/index');
    }
}
