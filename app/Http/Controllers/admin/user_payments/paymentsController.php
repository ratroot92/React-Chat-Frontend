<?php

namespace App\Http\Controllers\admin\user_payments;

use App\Models\user_payments\Mod_Payments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class paymentsController extends Controller
{
    public function payment_index($id)
    {
        $peyment_method         = Mod_Payments::where('user_id','=',$id)->get();
        return view('pages.user_payments',
                ['peyment_method' => $peyment_method]
        );
    }
    public function save_payement(Request $request)
    {
        $input                                       = $request->all();
        $payement_method                             = new Mod_Payments();
        $payement_method->user_id                    = $input['userId'];
        $payement_method->payment_name               = $input['payment_name'];
        $payement_method->payment_description        = $input['payment_description'];
        $payement_method->status                     = $input['status'];
        $payement_method->save();

        $payement_method      = Mod_Payments::where('user_id','=',$input['userId'])->get();
        return response()->json($payement_method);
    }
    public function edit_payment($paymentid,Request $request)
    {
        $payement_method       = Mod_Payments::findorfail($paymentid);
        return view('pages.edit_user_payment',
                  ['payement_method' =>$payement_method]
            );

    }
    public function update_payment($paymentid,Request $request)
    {
        $input                                      = $request->all();
        $payement_method                            = Mod_Payments::findorfail($paymentid);
        $payement_method->user_id                   = $input['userId'];
        $payement_method->payment_name              = $input['payment_name'];
        $payement_method->payment_description       = $input['payment_description'];
        $payement_method->status                    = $input['status'];
        $payement_method->save();

        $payement_method      = Mod_Payments::where('user_id','=',$input['userId'])->get();
        return response()->json($payement_method);
    }
    public function delete_payement($payment_id,$user_id)
    {
        $payment_method     = Mod_Payments::where('id','=',$payment_id)
                                            ->where('user_id','=',$user_id)
                                             ->delete();
        $payment_method    = Mod_Payments::where('user_id','=',$user_id)->get();
        return response()->json($payment_method);
    }
}
