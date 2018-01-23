<?php

namespace App\Http\Controllers\Pin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class indexcontroller extends Controller
{
    public function index()
    {
    	return view('pin.setPIN');
    }
}
