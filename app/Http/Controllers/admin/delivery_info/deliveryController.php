<?php

namespace App\Http\Controllers\admin\delivery_info;

use App\Models\delivery_info\Mod_deliveryinfo;
use App\Models\user_orders\Mod_orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class deliveryController extends Controller
{
    public function save_delivery_info(Request $request)
    {
        $order                      = Mod_orders::where('user_id','=',Auth::user()->id)->orderby('id','DESC')->pluck('id')->first();
        if($order=="")
        {
            $ordrID = 1;
        }
        else
        {
            $ordrID = $order[0]+1;
        }
        $input                      = $request->all();
        $delivery                   = new Mod_deliveryinfo();
        $delivery->user_id          = Auth::user()->id;
        $delivery->order_id         = $ordrID;
        $delivery->city             = $input['city'];
        $delivery->zone             = $input['zone'];
        $delivery->address          = $input['address'];
        $delivery->save();
        return response()->json('Delivery info has been added successfully');
    }
}
