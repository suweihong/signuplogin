<?php

namespace App\Http\Controllers\Pin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

use App\Models\User;
use App\Models\Event; 
class indexcontroller extends Controller
{
	//设置Pin的页面
    public function index(Request $request)
    {
    	return view('pin.index');
    }
    //设置的pin 加入数据库
     public function doIndex(Request $request)
    {
    	$pin=$request->input('pin','');
    	$api_token=$request->route('api_token');
    	// validate
        $validate = Validator::make($request->all(), [
            'pin' => ['required',
            		   'regex:/^[0-9]{6}$/'
        				],

        ], [
            'pin.required'=> trans('login.pin必须填'),
            'pin.regex'=> trans('login.pin必须为6位数字'),
        ]);
 
        // validate fail
        if($validate->fails()){
            return response()->json([
                'errcode' => 1001,
                'errmsg' => $validate->errors()->first(),
            ]);
        }
        //验证api_token
        $user=User::where('api_token',$api_token)->first();
        //api_token无效
        if(!$user){
        	return response()->json([
        		'errcode' =>1002,
        		'errmsg' => trans('api_token无效'),
        	]);
        }

        //save 
        $user->pin=$pin;
        if(!$user->save()){
        	return response()->json([
        		'errcode' =>9001,
        		'errmsg' =>trans('system.9001'),
        	]);
        }
        //加入事件
        $agent=new Agent();
        $event=new Event;
        $event->user_id=$user->id;
        $event->ipaddr=$request->getClientIp();
        $event->device=$agent->browser().' on '.$agent->platform();
        $event->action='PIN_SET';
        $event->save();
        //success
        return response()->json([
        	'errcode'=>0,
        	'errmsg'=>trans('pin设置成功'),
        ]);
    	
    }
    //重置Pin的页面
    public function verified(Request $request)
    {
    	return view('pin.setPIN');
    }
    //重置Pin的操作
     public function doVerified(Request $request)
    {
    	
    }
}
