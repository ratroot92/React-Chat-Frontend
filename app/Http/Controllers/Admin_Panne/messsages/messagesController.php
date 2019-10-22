<?php

namespace App\Http\Controllers\Admin_Panne\messsages;

use App\Models\user_messages\Mod_user_messages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class messagesController extends Controller
{
    public function index()
    {
        $message            = Mod_user_messages::all();
        return view('admin.pages.messages',[
            'message'      => $message
        ]);
    }
    public function view($id)
    {
        $message           = Mod_user_messages::findorfail($id);
        $message->status   = 1;
        $message->save();
        $message1           = Mod_user_messages::where('id','=',$id)->get();
        return response()->json($message1);
    }
}
