<?php

namespace App\Http\Controllers\admin\user_messages;

use App\Models\user_messages\Mod_user_messages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class user_messageController extends Controller
{
   public function save_message(Request $request)
   {
      $input                     = $request->all();
      $message                   = new Mod_user_messages();
      $message->user_name        = $input['name'];
       $message->user_email      = $input['email'];
       $message->phone_no        = $input['phone_no'];
       $message->title           = $input['title'];
       $message->user_message    = $input['message'];
       $message->status          = 0;
       $message->save();
       return response()->json('Your query has been submitted successfully');
   }
}
