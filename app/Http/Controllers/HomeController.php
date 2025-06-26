<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->forget('student'); // We want to clear this so that no student is logged in when the user visits the home page.
        return view('index');
    }
}
