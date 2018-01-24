<?php

namespace App\Http\Controllers\Pin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class indexcontroller extends Controller
{
	//设置Pin的页面
    public function index()
    {
    	return view('pin.index');
    }
    //设置的pin 加入数据库
     public function doIndex()
    {
    	
    }
    //重置Pin的页面
    public function verified()
    {
    	return view('pin.setPIN');
    }
    //重置Pin的操作
     public function doVerified()
    {
    	
    }
}
