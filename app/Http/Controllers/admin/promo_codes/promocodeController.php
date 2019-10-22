<?php

namespace App\Http\Controllers\admin\promo_codes;

use App\Models\promo_code\Mod_promocode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class promocodeController extends Controller
{
    public function Add_Promo(Request $request,$amount)
    {
        $input          = $request->all();
        $promo          = Mod_promocode::where('code','=',$input['promo_code'])
                          ->where('status','=',1)
                          ->pluck('discount_percentage');
        $final_amount   = ($promo[0]/100.0)*$amount;
        return response()->json($final_amount);
    }
}
