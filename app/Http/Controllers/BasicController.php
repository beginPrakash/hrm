<?php

namespace App\Http\Controllers;
use App\Models\Holidays;


use Illuminate\Http\Request;
use Session;

class BasicController extends Controller
{

    public function index(Request $request)
    { 
        return view('basic.docs');
    }

}
