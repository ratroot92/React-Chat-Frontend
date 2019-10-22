<?php

namespace App\Http\Controllers\admin\user_privacy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrivacyController extends Controller
{
    public function privacy_index()
    {
        return view('pages.user_privacy');
    }
}
