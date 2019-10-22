<?php

namespace App\Http\Controllers\admin\News_letters;

use App\Models\news_letter\Mod_news_letters;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class news_lettersController extends Controller
{
    public function save_news_letter(Request $request)
    {
        $input                 = $request->all();
        $news_letter           = new Mod_news_letters();
        $news_letter->email    = $input['email'];
        $news_letter->status   = 1;
        $news_letter->save();
        return response()->json('You have been subscribe successfully');
    }
}
